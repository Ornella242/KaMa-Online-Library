<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\PublicationService;
use App\Models\Category;
use App\Models\Payment;
use Illuminate\Http\Request;
use Spatie\PdfToImage\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Notifications\BookUnderReviewNotification;
use App\Notifications\BookPublishedNotification;
use App\Notifications\BookRejectedNotification;
use App\Notifications\BookRevisionRequiredNotification;
use App\Models\User;
use Imagick;

class BooksController extends Controller
{

    public function listBooks(Request $request)
    {
        $userId = Auth::id();

        $query = Book::query()->where('user_id', $userId)
            ->with([
                'category',
                'subcategory',
                'publicationPayment',
                'activeSponsorship',
            ]);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $books = $query->paginate(9)->withQueryString();

        $authorBooks = Book::query()->where('user_id', $userId);
        $totalBooks = (clone $authorBooks)->count();
        $draftBooks = (clone $authorBooks)->where('status', 'draft')->count();
        $pendingBooks = (clone $authorBooks)->where('status', 'waiting_review')->count();
        $reviewBooks = (clone $authorBooks)->where('status', 'under_review')->count();
        $publishedBooks = (clone $authorBooks)->where('status', 'published')->count();
        $revisionBooks = (clone $authorBooks)->where('status', 'revision_required')->count();

        $publicationFees = \App\Models\PublicationFee::query()
            ->get()
            ->keyBy('book_type');

        return view(
            'admin.books.index',
            compact(
                'books',
                'totalBooks',
                'draftBooks',
                'pendingBooks',
                'reviewBooks',
                'publishedBooks',
                'revisionBooks',
                'publicationFees'
            )
        );
    }

    public function create()
    {
        $categories = Category::with('subcategories')->get();

        $categoryOptions = $categories->mapWithKeys(function ($category) {
            return [
                (string) $category->id => $category->subcategories
                    ->map(fn ($sub) => [
                        'id' => $sub->id,
                        'name' => $sub->name,
                    ])
                    ->values()
                    ->all(),
            ];
        });

        return view('admin.books.create', compact('categories', 'categoryOptions'));
    }

    public function getSubcategories(int $category)
    {
        $subcategories = \App\Models\Subcategory::query()
                    ->where('category_id', $category)
                    ->get();
        return response()->json($subcategories);
    }

      public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => [
                'required',
                'exists:categories,id'
            ],
            'subcategory_id' => [
                'required',
                'exists:subcategories,id'
            ],
            'short_description' => 'required|string',
            'type' => [
                'required',
                'in:ebook,audio'
            ],

            'price' => 'required|numeric|min:0',

            'pages' => [
                'required_if:type,ebook',
                'nullable',
                'integer',
                'min:1'
            ],

            'duration' => [
                'required_if:type,audio',
                'nullable',
                'string'
            ],

            'language' => 'required|string|max:50',

            'publication_year' => 'required|digits:4',

            'cover_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],

            'ebook_file' => [
                'required_if:type,ebook',
                'file',
                'mimes:pdf',
                'max:102400'
            ],

            'audio_file' => [
                'required_if:type,audio',
                'file',
                'mimes:mp3',
                'max:102400'
            ],
            'preview_type' => 'required|in:text,pages',
            'long_description' => 'required_if:preview_type,text|nullable|string',
            'preview_start_page' => 'required_if:preview_type,pages|nullable|integer|min:1',
            'preview_end_page' => 'required_if:preview_type,pages|nullable|integer|gte:preview_start_page',
            'copyright_accepted' => [
                'required',
                'accepted'
            ],
        ]);


        if ($request->preview_type === 'pages') {
            $totalPreviewPages = $request->preview_end_page - $request->preview_start_page + 1;
            if ($totalPreviewPages > 5) {
                return back()
                    ->withErrors([
                        'preview_end_page' => 'Vous pouvez sélectionner au maximum 5 pages.'
                    ])
                    ->withInput();
            }
        }
        

        // Upload couverture
        $coverPath = $request
            ->file('cover_image')
            ->store('books/covers', 'public');


        // Upload ebook/audio
        if ($request->type === 'ebook') {

            $file = $request->file('ebook_file');
            $fileType = 'pdf';

        } else {

            $file = $request->file('audio_file');
            $fileType = 'mp3';

        }

        $fileName = Str::slug(
            pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
        )
        .'.'.$file->getClientOriginalExtension();

        if($request->type === 'ebook'){
            $filePath = $file->storeAs(
            'ebooks',
            $fileName,
            'local'
        );
        } else {
               $filePath = $file->storeAs(
            'audios',
            $fileName,
            'local'
        );
        }

        if (
            $request->type === 'ebook'
            && $request->preview_type === 'pages'
        ) {
            $pdfAbsolutePath = Storage::disk('local')->path($filePath);
            $pageCount = $this->getPdfPageCount($pdfAbsolutePath);

            if (
                $pageCount !== null
                && (int) $request->preview_end_page > $pageCount
            ) {
                Storage::disk('local')->delete($filePath);
                Storage::disk('public')->delete($coverPath);

                return back()
                    ->withErrors([
                        'preview_end_page' => "Ce PDF ne contient que {$pageCount} page(s). Choisissez une plage entre 1 et {$pageCount}.",
                    ])
                    ->withInput();
            }
        }
     
       $fileSize = $file->getSize();

        $book = Book::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'title' => $request->title,
            'short_description' => $request->short_description,
            'long_description' => $request->preview_type == 'text'
                                ? $request->long_description
                                : null,
            'preview_type' => $request->preview_type,
            'preview_start_page' => $request->preview_type == 'pages'
                ? $request->preview_start_page
                : null,
            'preview_end_page' => $request->preview_type == 'pages'
                ? $request->preview_end_page
                : null,
            'duration' => $request->duration,
            'type' => $request->type,
            'price' => $request->price,
            'pages' => $request->pages,
            'language' => $request->language,
            'publication_year' => $request->publication_year,
            // fichiers
            'cover_image' => $coverPath,
            'file_path' => $filePath,
            'original_file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'status' => 'published',
            'copyright_accepted' => true,
            'copyright_accepted_at' => now(),
        ]);

        if (
            $book->type === 'ebook' &&
            $book->preview_type === 'pages'
        ) {
            try {
                $this->generatePreviewPages($book);
            } catch (\Throwable $exception) {
                report($exception);

                return redirect()
                    ->route('admin.books.index')
                    ->with('book_created', true)
                    ->with(
                        'error',
                        'Le livre a été publié, mais l’extrait PDF n’a pas pu être généré : '.$exception->getMessage()
                    );
            }
        }

        return redirect()
            ->route('admin.books.index')
            ->with('book_created', true);
    }

    public function show(Book $book, PublicationService $publicationService)
    {
        $book->load(['author', 'category', 'subcategory']);

        $previewStart = $book->preview_start_page;
        $previewEnd = $book->preview_end_page;
        $activeSponsorship = $book->activeSponsorship()->with('plan')->first();
        $isOwnBook = $this->isOwnBook($book);

        $pendingPublicationPayment = $book->payments()
            ->where('type', 'publication')
            ->where('status', 'pending')
            ->latest()
            ->first();

        $depositPaid = $book->payments()
            ->where('type', 'publication')
            ->where('status', 'success')
            ->exists();

        $paymentRequired = $publicationService->shouldRequirePayment();


        return view('admin.books.show', compact(
            'book',
            'previewStart',
            'previewEnd',
            'activeSponsorship',
            'isOwnBook',
            'pendingPublicationPayment',
            'depositPaid','paymentRequired'
        ));
    }

    public function edit(Book $book)
    {
        abort_unless($this->isOwnBook($book), 403, 'Vous ne pouvez modifier que vos propres livres.');

        $categories = Category::all();
        $subcategories = $book->category->subcategories;

        $isAudioBook = $book->type === 'audio';
        $existingFileName = $book->original_file_name
            ?: ($book->file_path ? basename($book->file_path) : null);
        $existingPreviewUrl = $book->file_path
            ? ($isAudioBook
                ? route('admin.books.audio', $book)
                : route('admin.books.preview.file', $book))
            : null;
        $existingFileSize = $book->file_size
            ? number_format($book->file_size / 1048576, 2).' MB'
            : null;

        return view('admin.books.edit', compact(
            'book',
            'categories',
            'subcategories',
            'isAudioBook',
            'existingFileName',
            'existingPreviewUrl',
            'existingFileSize'
        ));
    }

    public function update(Request $request, Book $book)
    {
        abort_unless($this->isOwnBook($book), 403, 'Vous ne pouvez modifier que vos propres livres.');
        // Admin peut modifier à tout statut — on saute la branche "published seul" et on tombe dans le code complet

        if (false) {

            $request->validate([

                'category_id' => [
                    'required',
                    'exists:categories,id'
                ],

                'subcategory_id' => [
                    'required',
                    'exists:subcategories,id'
                ],

                'language' => [
                    'required',
                    'string',
                    'max:50'
                ],

                'publication_year' => [
                    'required',
                    'digits:4'
                ],

                'price' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'short_description' => [
                    'required',
                    'string'
                ],

               'preview_type' => [
                    'required_if:type,ebook',
                    'nullable',
                    'in:text,pages'
                ],

                'long_description' => [
                    'required_if:preview_type,text',
                    'nullable',
                    'string'
                ],

               'preview_start_page' => [
                    'required_if:preview_type,pages',
                    'nullable',
                    'integer',
                    'min:1'
                ],

                'preview_end_page' => [
                    'required_if:preview_type,pages',
                    'nullable',
                    'integer',
                    'gte:preview_start_page'
                ],

            ]);


            /*
            |--------------------------------------------------------------------------
            | Sauvegarde ancien aperçu
            |--------------------------------------------------------------------------
            */

            $oldPreviewType = $book->preview_type;

            $oldStartPage = $book->preview_start_page;

            $oldEndPage = $book->preview_end_page;



            /*
            |--------------------------------------------------------------------------
            | Vérification changement aperçu
            |--------------------------------------------------------------------------
            */

            $previewChanged =
                $oldPreviewType !== $request->preview_type ||
                $oldStartPage != $request->preview_start_page ||
                $oldEndPage != $request->preview_end_page;



            /*
            |--------------------------------------------------------------------------
            | Mise à jour informations autorisées
            |--------------------------------------------------------------------------
            */

            $book->update([

                'category_id' => $request->category_id,

                'subcategory_id' => $request->subcategory_id,

                'language' => $request->language,

                'publication_year' => $request->publication_year,

                'price' => $request->price,

                'short_description' => $request->short_description,

                'preview_type' => $book->type === 'audio'
                                ? 'text'
                                : $request->preview_type,

                'long_description' => $book->type === 'audio'
                ? $request->long_description
                : (
                    $request->preview_type === 'text'
                        ? $request->long_description
                        : null
                ),

                'preview_start_page' => $book->type === 'ebook' && $request->preview_type === 'pages'
                    ? $request->preview_start_page
                    : null,

                'preview_end_page' => $book->type === 'ebook' && $request->preview_type === 'pages'
                    ? $request->preview_end_page
                    : null,

            ]);



            /*
            |--------------------------------------------------------------------------
            | Suppression anciennes pages preview
            |--------------------------------------------------------------------------
            */

           if (
                $previewChanged &&
                $oldPreviewType === 'pages'
            ) {

                Storage::disk('local')
                    ->deleteDirectory(
                        'books/previews/'.$book->id
                    );

            }

            /*
            |--------------------------------------------------------------------------
            | Génération nouvelles pages preview
            |--------------------------------------------------------------------------
            */

            if (
                $previewChanged &&
                $book->preview_type === 'pages'
            ) {

                $this->generatePreviewPages($book);

            }



            return redirect()
                ->route('admin.books.index')
                ->with(
                    'success',
                    'Les informations du livre ont été mises à jour.'
                );
        }


        // Mise à jour complète (admin auteur)
        $request->validate([

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'category_id' => [
                'required'
            ],

            'subcategory_id' => [
                'required'
            ],

            'type' => [
                'required',
                'in:ebook,audio'
            ],

            'language' => [
                'required'
            ],

            'publication_year' => [
                'required'
            ],

            'price' => [
                'required',
                'numeric'
            ],

            'pages' => [
                'required_if:type,ebook',
                'nullable',
                'integer',
                'min:1'
            ],

            'duration' => [
                'required_if:type,audio',
                'nullable',
                'string'
            ],

            'short_description' => [
                'required'
            ],

            'preview_type' => [
                'required_if:type,ebook',
                'nullable',
                'in:text,pages'
            ],

            'long_description' => [
                'required_if:preview_type,text',
                'nullable',
                'string'
            ],

            'preview_start_page' => [
                'required_if:preview_type,pages',
                'nullable',
                'integer',
                'min:1'
            ],

            'preview_end_page' => [
                'required_if:preview_type,pages',
                'nullable',
                'integer',
                'gte:preview_start_page'
            ],

            // Couverture
            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],


            // Fichiers livre
            'ebook_file' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:102400'
            ],

            'audio_file' => [
                'nullable',
                'file',
                'mimes:mp3',
                'max:102400'
            ],

        ]);

        if (
            $request->type === 'ebook'
            && $request->preview_type === 'pages'
            && ((int) $request->preview_end_page - (int) $request->preview_start_page + 1) > 5
        ) {
            return back()
                ->withErrors([
                    'preview_end_page' => 'Vous pouvez sélectionner au maximum 5 pages consécutives.',
                ])
                ->withInput();
        }

        /*Vérification changement de type*/

        if(
            $book->type !== $request->type &&
            !$request->hasFile('ebook_file') &&
            !$request->hasFile('audio_file')
        ){

            return back()
                ->withErrors([
                    'file' => 'Veuillez télécharger le fichier correspondant au nouveau type.'
                ])
                ->withInput();

        }

        $oldPreviewType = $book->preview_type;
        $oldStartPage = $book->preview_start_page;
        $oldEndPage = $book->preview_end_page;

        $previewType = $request->type === 'audio'
            ? 'text'
            : ($request->preview_type ?: 'text');

        /* Données du livre*/

        $data = [

            'title' => $request->title,

            'category_id' => $request->category_id,

            'subcategory_id' => $request->subcategory_id,

            'type' => $request->type,

            'language' => $request->language,

            'publication_year' => $request->publication_year,

            'price' => $request->price,

            'pages' => $request->type === 'ebook' ? $request->pages : null,

            'duration' => $request->type === 'audio' ? $request->duration : null,

            'short_description' => $request->short_description,

            'preview_type' => $previewType,

            'long_description' => $previewType === 'text'
                ? $request->long_description
                : null,

            'preview_start_page' => $request->type === 'ebook' && $previewType === 'pages'
                ? $request->preview_start_page
                : null,

            'preview_end_page' => $request->type === 'ebook' && $previewType === 'pages'
                ? $request->preview_end_page
                : null,

        ];

        $previewChanged =
            $oldPreviewType !== $data['preview_type']
            || (int) $oldStartPage !== (int) ($data['preview_start_page'] ?? 0)
            || (int) $oldEndPage !== (int) ($data['preview_end_page'] ?? 0);

        /* Mise à jour couverture */

        if($request->hasFile('cover_image')){
            if(
                $book->cover_image &&
                Storage::disk('public')->exists($book->cover_image)
            ){

                Storage::disk('public')
                    ->delete($book->cover_image);

            }

            $data['cover_image'] = $request
                ->file('cover_image')
                ->store(
                    'books/covers',
                    'public'
                );

        }

        /* Mise à jour fichier ebook/audio*/

        $file = null;
        if(
            $request->type === 'ebook' &&
            $request->hasFile('ebook_file')
        ){
            $file = $request->file('ebook_file');
        }

        elseif(
            $request->type === 'audio' &&
            $request->hasFile('audio_file')
        ){
            $file = $request->file('audio_file');
        }

        if($file){
            // Suppression ancien fichier
            if(
                $book->file_path &&
                Storage::disk('local')->exists($book->file_path)
            ){

                Storage::disk('local')
                    ->delete($book->file_path);

            }

            // Nouveau nom propre
            $fileName = time().'_'.
                Str::slug(
                    pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    )
                )
                .'.'.$file->getClientOriginalExtension();

            // Stockage selon le type
            if($request->type === 'ebook'){
                $filePath = $file->storeAs(
                    'ebooks',
                    $fileName,
                    'local'
                );
            }else{
                $filePath = $file->storeAs(
                    'audios',
                    $fileName,
                    'local'
                );
            }

            $data['file_path'] = $filePath;
            $data['original_file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['file_size'] = $file->getSize();

            $previewChanged = $previewChanged || ($previewType === 'pages');
        }

        /* Enregistrement */
        $book->update($data);

        if ($previewChanged && $oldPreviewType === 'pages') {
            Storage::disk('local')->deleteDirectory('books/previews/'.$book->id);
        }

        if ($previewChanged && $book->fresh()->preview_type === 'pages') {
            try {
                $this->generatePreviewPages($book->fresh());
            } catch (\Throwable $exception) {
                report($exception);

                return redirect()
                    ->route('admin.books.edit', $book)
                    ->with(
                        'error',
                        'Les informations ont été enregistrées, mais l’extrait PDF n’a pas pu être régénéré : '.$exception->getMessage()
                    );
            }
        }

        return redirect()
            ->route('admin.books.index')
            ->with(
                'success',
                'Livre modifié avec succès.'
            );
    }

    public function destroy(Book $book)
    {
        abort_unless($this->isOwnBook($book), 403);

        if (
            $book->cover_image &&
            Storage::disk('public')->exists($book->cover_image)
        ) {
            Storage::disk('public')->delete($book->cover_image);
        }

        if ($book->file_path && Storage::disk('local')->exists($book->file_path)) {
            Storage::disk('local')->delete($book->file_path);
        }

        $book->delete();

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Livre supprimé avec succès.');
    }

    public function deposit(Book $book)
    {
        abort_unless($this->isOwnBook($book), 403);
        abort_unless($book->status === 'draft', 409, 'Ce livre ne peut pas être payé dans son état actuel.');

        $fee = \App\Models\PublicationFee::forType($book->type);
        abort_unless(
            $fee && (float) $fee->amount > 0,
            503,
            'Les frais de publication ne sont pas encore configurés pour ce format.'
        );
        abort_unless(
            strtoupper($fee->currency) === 'XOF',
            503,
            'Le tarif doit être enregistré en XOF pour être utilisé avec KKiaPay.'
        );

        $kkiapayPublicKey = config('services.kkiapay.public_key');
        $kkiapaySandbox = (bool) config('services.kkiapay.sandbox', true);
        $kkiapayConfigured = filled($kkiapayPublicKey)
            && filled(config('services.kkiapay.private_key'))
            && filled(config('services.kkiapay.secret'));

        return view(
            'admin.books.deposit',
            compact(
                'book',
                'fee',
                'kkiapayPublicKey',
                'kkiapaySandbox',
                'kkiapayConfigured'
            )
        );
    }

    public function resubmit(Book $book)
    {
        abort_unless($this->isOwnBook($book), 403);

        if ($book->status !== 'revision_required') {
            abort(403);
        }

        $book->update([
            'status' => 'waiting_review',
            'rejection_reason' => null,
        ]);

        $admins = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->where('id', '!=', Auth::id())->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\BookResubmittedNotification($book));
        }

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Votre livre a été renvoyé pour vérification éditoriale.');
    }

     public function generatePreviewPages(Book $book)
    {
        if ($book->type !== 'ebook' || $book->preview_type !== 'pages') {
            return;
        }

        if (! $book->file_path || ! Storage::disk('local')->exists($book->file_path)) {
            throw new \RuntimeException('Le fichier PDF du livre est introuvable.');
        }

        $pdfPath = Storage::disk('local')->path($book->file_path);
        $folder = storage_path('app/private/books/previews/'.$book->id);

        if (! file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $start = (int) $book->preview_start_page;
        $end = (int) $book->preview_end_page;

        if ($start < 1 || $end < $start || ($end - $start + 1) > 5) {
            throw new \RuntimeException('La plage de pages d’aperçu est invalide (1 à 5 pages consécutives).');
        }

        $pageCount = $this->getPdfPageCount($pdfPath);
        if ($pageCount !== null && $end > $pageCount) {
            throw new \RuntimeException(
                "Ce PDF ne contient que {$pageCount} page(s). Choisissez une plage entre 1 et {$pageCount}."
            );
        }

        $pdftoppm = $this->resolvePdftoppmBinary();

        for ($pageNumber = $start; $pageNumber <= $end; $pageNumber++) {
            $tmpPrefix = $folder.'/tmp-'.$pageNumber;
            $command = sprintf(
                '%s -png -singlefile -r 150 -f %d -l %d %s %s 2>&1',
                escapeshellarg($pdftoppm),
                $pageNumber,
                $pageNumber,
                escapeshellarg($pdfPath),
                escapeshellarg($tmpPrefix)
            );

            $output = [];
            $exitCode = 0;
            exec($command, $output, $exitCode);
            $source = $tmpPrefix.'.png';

            if ($exitCode !== 0 || ! file_exists($source)) {
                $details = trim(implode("\n", $output));
                throw new \RuntimeException(
                    'Impossible de générer l’extrait PDF'
                    .($details !== '' ? ' : '.$details : '.')
                );
            }

            $image = imagecreatefrompng($source);
            if ($image === false) {
                @unlink($source);
                throw new \RuntimeException('Impossible de lire la page générée.');
            }

            imagewebp($image, $folder.'/page-'.$pageNumber.'.webp', 85);
            imagedestroy($image);
            unlink($source);
        }

        return 'Preview généré';
    }

    private function resolvePdftoppmBinary(): string
    {
        foreach (['/usr/local/bin/pdftoppm', '/opt/homebrew/bin/pdftoppm'] as $candidate) {
            if (is_executable($candidate)) {
                return $candidate;
            }
        }

        $output = [];
        $exitCode = 0;
        exec('command -v pdftoppm 2>/dev/null', $output, $exitCode);
        if ($exitCode === 0 && ! empty($output[0]) && is_executable($output[0])) {
            return $output[0];
        }

        throw new \RuntimeException(
            'L’outil pdftoppm (Poppler) n’est pas installé ou inaccessible pour PHP.'
        );
    }

    private function getPdfPageCount(string $pdfPath): ?int
    {
        foreach (['/usr/local/bin/pdfinfo', '/opt/homebrew/bin/pdfinfo', 'pdfinfo'] as $binary) {
            $command = $binary === 'pdfinfo'
                ? 'pdfinfo '.escapeshellarg($pdfPath).' 2>/dev/null'
                : (is_executable($binary)
                    ? escapeshellarg($binary).' '.escapeshellarg($pdfPath).' 2>/dev/null'
                    : null);

            if ($command === null) {
                continue;
            }

            $output = [];
            $exitCode = 0;
            exec($command, $output, $exitCode);
            if ($exitCode !== 0) {
                continue;
            }

            foreach ($output as $line) {
                if (preg_match('/^Pages:\s+(\d+)/i', $line, $matches)) {
                    return (int) $matches[1];
                }
            }
        }

        return null;
    }

    public function previewPage(Book $book, $page)
    {

        // Vérifier que la page demandée est autorisée

        if(
            $page < $book->preview_start_page ||
            $page > $book->preview_end_page
        ){

            abort(403);

        }



        $path = storage_path(
            'app/private/books/previews/'
            .$book->id.
            '/page-'.$page.'.webp'
        );



        if(!file_exists($path)){

            abort(404);

        }



        return response()->file($path,[

            'Content-Type'=>'image/webp',

            'Cache-Control'=>'private, max-age=3600',

            'X-Robots-Tag'=>'noindex'

        ]);

    }

    public function previewPdf(Book $book)
    {

        abort_unless(
            $book->preview_type === 'pages',
            403
        );

        $path = storage_path(
            'app/public/' . $book->file_path
        );

        if(!file_exists($path)){
            abort(404);
        }

        return response()->file($path, [
            'Content-Type'=>'application/pdf',
            'Content-Disposition'=>'inline',
            'X-Robots-Tag'=>'noindex'
        ]);

    }

    public function previewFile(Book $book)
    {
        $this->authorize('viewFile', $book);


        if ($book->type !== 'ebook') {
            abort(404);
        }


        if (!Storage::disk('local')->exists($book->file_path)) {
            abort(404);
        }


        return response()->file(
            Storage::disk('local')->path($book->file_path)
        );
    }

    public function streamAudio(Book $book)
    {
        // Vérifie que l'auteur est bien propriétaire
        $this->authorize('viewFile', $book);


        if ($book->type !== 'audio') {
            abort(404);
        }


        if (!Storage::disk('local')->exists($book->file_path)) {
            abort(404);
        }


        return response()->file(
            Storage::disk('local')->path($book->file_path),
            [
                'Content-Type' => 'audio/mpeg',
            ]
        );
    }

    public function boost(Book $book)
    {
        abort_if($book->user_id !== Auth::id(), 403);

        $social = Auth::user()->socialProfile;

        return view('admin.books.boost', compact('book', 'social'));
    }

    public function allBooks(Request $request)
    {
        // Tous les livres de la plateforme
        $query = Book::with([
            'author',
            'category',
            'subcategory',
            'publicationPayment',
        ]);

        // FILTRE PAR STATUT
        if (
            $request->filled('status') &&
            in_array($request->string('status')->toString(), Book::STATUSES, true)
        ) {
            $query->where('status', $request->string('status')->toString());
        }

        // RECHERCHE
        if($request->filled('search')){
            $query->where(function($q) use ($request){
                $q->where(
                    'title',
                    'like',
                    '%'.$request->search.'%'
                )

                ->orWhereHas('author', function($author) use ($request){

                    $author->where('firstname','like','%'.$request->search.'%')
                        ->orWhere('lastname','like','%'.$request->search.'%');

                });

            });

        }



        $books = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Statistiques

        $totalBooks = Book::count();


        $pendingBooks = Book::query()->where('status','waiting_review')
            ->count();


        $publishedBooks = Book::query()->where('status','published')
            ->count();


        $reviewBooks = Book::query()->where('status','under_review')
            ->count();

        $revisionBooks = Book::query()->where('status', 'revision_required')
            ->count();



        return view('admin.books.allbooks', compact(
            'books',
            'totalBooks',
            'pendingBooks',
            'publishedBooks',
            'reviewBooks',
            'revisionBooks'
        ));
    }

    public function review(Book $book)
    {
        if ($this->isOwnBook($book)) {
            return back()->with(
                'error',
                'Vous ne pouvez pas valider éditorialement vos propres livres. Utilisez un autre compte admin.'
            );
        }

        if ($book->status !== 'waiting_review') {
            return back()->with('error', 'Seul un livre en attente peut passer en vérification.');
        }

        // Vérifier seulement si ce livre nécessitait un dépôt
        if ($book->payment_required) {
            $payment = Payment::query()
                ->where('book_id', $book->id)
                ->where('type', 'publication')
                ->where('status', 'success')
                ->exists();

            if (!$payment) {
                return back()->with(
                    'error',
                    'Le paiement du dépôt est requis avant la vérification.'
                );
            }
        }

        $book->update([
            'status'=>'under_review'
        ]);

        $this->notifyAuthorSafely(
            $book,
            new BookUnderReviewNotification($book)
        );

        return redirect()
            ->route('admin.books.all', ['status' => Book::STATUS_UNDER_REVIEW])
            ->with(
                'success',
                'Le livre est maintenant en vérification éditoriale.'
            );

    }

    public function editorialQueue()
    {
        $books = Book::with([
            'author',
            'category',
            'subcategory'
        ])
        ->where('status','under_review')
        ->latest()
        ->paginate(4);


     $month = now()->month;
     $year = now()->year;


    $waitingReviewBooks = Book::query()
        ->where('status','waiting_review')
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->count();



    $reviewBooks = Book::query()
        ->where('status','under_review')
        ->whereMonth('updated_at', $month)
        ->whereYear('updated_at', $year)
        ->count();



    $publishedBooks = Book::query()
        ->where('status','published')
        ->whereMonth('updated_at', $month)
        ->whereYear('updated_at', $year)
        ->count();



    $rejectedBooks = Book::query()
        ->where('status','revision_required')
        ->whereMonth('updated_at', $month)
        ->whereYear('updated_at', $year)
        ->count();

        return view(
            'admin.books.editorial-queue',
            compact(
                'books',
                'waitingReviewBooks',
                'reviewBooks',
                'publishedBooks',
                'rejectedBooks'
            )
        );
    }

    public function publish(Book $book)
    {
        if ($this->isOwnBook($book)) {
            return back()->with(
                'error',
                'Vous ne pouvez pas publier vos propres livres depuis la file éditoriale. Utilisez un autre compte admin.'
            );
        }

        if($book->status !== 'under_review'){
            return back()->with(
                'error',
                'Ce livre ne peut pas être publié actuellement.'
            );
        }

        $book->update([
            'status'=>'published'
        ]);

        $this->notifyAuthorSafely(
            $book,
            new BookPublishedNotification($book)
        );

        return back()->with(
            'success',
            'Le livre a été publié avec succès.'
        );
    }

    public function reject(Request $request, Book $book)
    {
        if ($this->isOwnBook($book)) {
            return back()->with(
                'error',
                'Vous ne pouvez pas retourner vos propres livres. Utilisez un autre compte admin.'
            );
        }

        if ($book->status !== 'under_review') {
            return back()->with('error', 'Seul un livre en cours de vérification peut être retourné.');
        }

        $request->validate([
            'reason'=>'required|string|max:1000'
        ]);

        $book->update([
            'status'=>'revision_required',
            'rejection_reason'=>$request->reason
        ]);

        $this->notifyAuthorSafely(
            $book,
            new BookRevisionRequiredNotification($book)
        );

        return back()->with(
            'success',
            'Le livre a été retourné à l’auteur pour correction.'
        );
    }

    private function isOwnBook(Book $book): bool
    {
        return (int) $book->user_id === (int) Auth::id();
    }

    private function notifyAuthorSafely(Book $book, object $notification): void
    {
        if (! $book->author) {
            return;
        }

        try {
            $book->author->notify($notification);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
