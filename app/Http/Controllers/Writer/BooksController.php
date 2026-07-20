<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\PdfToImage\Pdf;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\Subcategory;
use App\Models\PublicationFee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Imagick;
use App\Models\User;
use App\Notifications\BookResubmittedNotification;



class BooksController extends Controller
{


    /**
     * Liste des livres de l'auteur connecté
     */
    public function listBooks(Request $request)
    {

         $userId = Auth::id();


        $query = Book::query()->where('user_id', $userId)
            ->with([
                'category',
                'subcategory',
                'publicationPayment',
            ]);

        if($request->filled('search')){

            $query->where('title','like',
                '%'.$request->search.'%'
            );

        }

        if (
            $request->filled('status') &&
            in_array($request->string('status')->toString(), Book::STATUSES, true)
        ) {
            $query->where('status', $request->string('status')->toString());
        }


        if (
            $request->filled('type') &&
            in_array($request->string('type')->toString(), ['ebook', 'audio'], true)
        ) {
            $query->where('type', $request->string('type')->toString());
        }


        switch($request->sort){
            case 'oldest':

                $query->oldest();

            break;
            case 'price_high':

                $query->orderBy(
                    'price',
                    'desc'
                );

            break;

            case 'price_low':
                $query->orderBy(
                    'price',
                    'asc'
                );
            break;

            default:

                $query->latest();

            break;

        }


        $books = $query
            ->paginate(9)
            ->withQueryString();

        $authorBooks = Book::query()->where('user_id', $userId);
        $stats = [
            'total' => (clone $authorBooks)->count(),
            'published' => (clone $authorBooks)->where('status', Book::STATUS_PUBLISHED)->count(),
            'validation' => (clone $authorBooks)->whereIn('status', [
                Book::STATUS_WAITING_REVIEW,
                Book::STATUS_UNDER_REVIEW,
            ])->count(),
            'drafts' => (clone $authorBooks)->where('status', Book::STATUS_DRAFT)->count(),
            'revisions' => (clone $authorBooks)->where('status', Book::STATUS_REVISION_REQUIRED)->count(),
        ];

        $publicationFees = PublicationFee::query()
            ->get()
            ->keyBy('book_type');

        return view(
            'writer.books.index',
            compact('books', 'stats', 'publicationFees')
        );
    }


    /**
     * Afficher le formulaire d'ajout
     */
    public function create()
    {
        $categories = Category::with('subcategories')->get();

        return view('writer.books.create', compact('categories'));
    }

    public function getSubcategories(int $category)
    {
        $subcategories = \App\Models\Subcategory::query()
                    ->where('category_id', $category)
                    ->get();
        return response()->json($subcategories);
    }

   

    /**
     * Enregistrer un nouveau livre
     */
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
                'string',
                'regex:/^\d{1,3}:[0-5]\d:[0-5]\d$/'
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

        $fileName = Str::uuid().'-'.Str::slug(
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
     

       $fileSize = $file->getSize();

       $book = Book::create([
            // auteur connecté
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
            'status' => 'draft',
            'copyright_accepted' => true,
            'copyright_accepted_at' => now(),
        ]);

        if (
            $book->type === 'ebook' &&
            $book->preview_type === 'pages'
        ) {
            $this->generatePreviewPages($book);
        }

        return redirect()
            ->route('writer.books')
            ->with(
                'book_created',
                [
                    'book_id' => $book->id,
                    'message' => 'Votre livre a été ajouté avec succès. Veuillez régler les frais de publication afin de poursuivre sa mise en ligne.'
                ]
            );
    }

    public function show(Book $book)
    {
        $this->authorize('view', $book);

        $previewStart = $book->preview_start_page;
        $previewEnd = $book->preview_end_page;
        return view('writer.books.show', compact('book','previewStart',
        'previewEnd'));
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        abort_unless(
            in_array($book->status, ['draft', 'revision_required'], true),
            409,
            'Ce livre ne peut plus être modifié dans son état actuel.'
        );

        $categories = Category::all();

        $subcategories = $book->category->subcategories;

        return view('writer.books.edit', compact(
            'book',
            'categories',
            'subcategories'
        ));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);
        abort_unless(
            in_array($book->status, ['draft', 'revision_required'], true),
            409,
            'Ce livre ne peut plus être modifié dans son état actuel.'
        );

        if ($book->status === 'waiting_review' || $book->status === 'under_review') 
        {

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
                ->route('writer.books')
                ->with(
                    'success',
                    'Les informations du livre ont été mises à jour.'
                );
        }

        
        // Code book status draft
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
                'string',
                'regex:/^\d{1,3}:[0-5]\d:[0-5]\d$/'
            ],

            'short_description' => [
                'required'
            ],

            'long_description' => [
                'required'
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

        /* Données du livre*/

        $data = [

            'title' => $request->title,

            'category_id' => $request->category_id,

            'subcategory_id' => $request->subcategory_id,

            'type' => $request->type,

            'language' => $request->language,

            'publication_year' => $request->publication_year,

            'price' => $request->price,

            'pages' => $request->pages,

            'duration' => $request->duration,

            'short_description' => $request->short_description,

            'long_description' => $request->long_description,

        ];

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
            $fileType = 'pdf';
        }

        elseif(
            $request->type === 'audio' &&
            $request->hasFile('audio_file')
        ){
            $file = $request->file('audio_file');
            $fileType = 'mp3';
        }

        if($file){
            // Suppression ancien fichier
            if(
                $book->file_path &&
                Storage::disk('public')->exists($book->file_path)
            ){

                Storage::disk('public')
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
                    'books/files/ebooks',
                    $fileName,
                    'public'
                );


            }else{


                $filePath = $file->storeAs(
                    'books/files/audios',
                    $fileName,
                    'public'
                );


            }



            $data['file_path'] = $filePath;

            $data['file_type'] = $fileType;

            $data['file_size'] = $file->getSize();


        }

        /* Enregistrement */
        $wasRevisionRequired = $book->status === 'revision_required';

        $book->update($data);

        if ($wasRevisionRequired) {
            return $this->sendBackForReview($book);
        }

        return redirect()
            ->route('writer.books')
            ->with(
                'success',
                'Livre modifié avec succès.'
            );
    }

    public function resubmit(Book $book)
    {
        $this->authorize('update', $book);

        if ($book->status !== 'revision_required') {
            abort(403);
        }

        return $this->sendBackForReview($book);
    }

    /**
     * Renvoie un livre corrigé vers la validation éditoriale.
     */
    private function sendBackForReview(Book $book)
    {
        $book->update([
            'status' => 'waiting_review',
            'rejection_reason' => null,
        ]);

        $admins = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new BookResubmittedNotification($book));
        }

        return redirect()
            ->route('writer.books')
            ->with(
                'success',
                'Vos corrections ont été enregistrées et le livre a été renvoyé pour validation éditoriale.'
            );
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        abort_unless(
            in_array($book->status, ['draft', 'revision_required'], true),
            409,
            'Seul un brouillon ou un livre à corriger peut être supprimé.'
        );

        /* Supprimer la couverture */

        if(
            $book->cover_image &&
            Storage::disk('public')->exists($book->cover_image)
        ){

            Storage::disk('public')
                ->delete($book->cover_image);

        }

        /* Supprimer le fichier PDF / MP3 */

        if(
            $book->file_path &&
            Storage::disk('local')->exists($book->file_path)
        ){

            Storage::disk('local')
                ->delete($book->file_path);

        }

        /* Supprimer le livre */
        // $book->delete();
        \App\Models\Book::destroy($book->id);
        return redirect()
            ->route('writer.books')
            ->with(
                'success',
                'Livre supprimé avec succès.'
            );

    }

    public function deposit(Book $book)
    {
        $this->authorize('view', $book);

        abort_unless($book->status === 'draft', 409, 'Ce livre ne peut pas être payé dans son état actuel.');

        $fee = PublicationFee::forType($book->type);
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
            'writer.books.deposit',
            compact(
                'book',
                'fee',
                'kkiapayPublicKey',
                'kkiapaySandbox',
                'kkiapayConfigured'
            )
        );
    }

    public function boost(Book $book)
    {
        $this->authorize('update', $book);
        abort_unless($book->status === 'published', 409, 'Seul un livre publié peut être sponsorisé.');

        $social = Auth::user()->socialProfile;

        return view('writer.books.boost', compact('book', 'social'));
    }

    public function dashboard()
    {
        $books = Auth::user()
            ->books()
            ->latest()
            ->take(5)
            ->get();
        return view('writer.dashboard', compact('books'));
    }


    /**
     * Demande de publication après paiement
     */
    public function publish(Book $book)
    {
        $this->authorize('update', $book);

        abort_unless($book->status === 'draft', 409, 'Ce livre ne peut pas être soumis dans son état actuel.');

        return redirect()
            ->route('writer.books.deposit', $book);
    }

    public function generatePreviewPages(Book $book)
    {

        $pdfPath = storage_path(
            'app/private/'.$book->file_path
        );


        $folder = storage_path(
            'app/private/books/previews/'.$book->id
        );


        if(!file_exists($folder)){
            mkdir($folder,0755,true);
        }


        $start = $book->preview_start_page;
        $end = $book->preview_end_page;


        for($pageNumber = $start; $pageNumber <= $end; $pageNumber++){


            $tmpPrefix = $folder.'/tmp-'.$pageNumber;
            $command = sprintf(
                'pdftoppm -png -singlefile -r 150 -f %d -l %d %s %s 2>&1',
                $pageNumber,
                $pageNumber,
                escapeshellarg($pdfPath),
                escapeshellarg($tmpPrefix)
            );

            exec($command, $output, $exitCode);
            $source = $tmpPrefix.'.png';

            if ($exitCode !== 0 || !file_exists($source)) {
                throw new \RuntimeException('Impossible de générer l’extrait PDF.');
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


            return "Preview généré";

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
            Storage::disk('local')->path($book->file_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.basename($book->original_file_name ?: $book->file_path).'"',
                'X-Frame-Options' => 'SAMEORIGIN',
            ]
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
                'Content-Disposition' => 'inline; filename="'.basename($book->original_file_name ?: $book->file_path).'"',
            ]
        );
    }

}
