<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
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
use Imagick;

class BooksController extends Controller
{

    public function listBooks(Request $request)
    {

        $userId = Auth::id();

        $query = Book::query()->where('user_id', $userId)
            ->with([
                'category',
                'subcategory'
            ]);

        if($request->filled('search')){

            $query->where('title','like',
                '%'.$request->search.'%'
            );
        }

        if($request->filled('status')){
            $query->where(
                'status',
                $request->status
            );

        }

        if($request->filled('type')){

            $query->where(
                'type',
                $request->type
            );

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
            ->paginate(5)
            ->withQueryString();

        $totalBooks = Book::where('user_id', Auth::id())->count();

        $publishedBooks = Book::where('user_id', Auth::id())
            ->where('status', 'published')
            ->count();

        $soldBooks = Payment::where('type', 'purchase')
            ->whereHas('book', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->count();


        return view(
            'admin.books.index',
            compact('books','totalBooks','publishedBooks','soldBooks')
        );
    }


      public function create()
    {
        $categories = Category::with('subcategories')->get();

        return view('admin.books.create', compact('categories'));
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
            $this->generatePreviewPages($book);
        }

        return redirect()
            ->route('admin.books.index')
            ->with(
                'success',
                'Votre livre a été ajouté avec succès.'
            );
    }

    public function show(Book $book)
    {
        $previewStart = $book->preview_start_page;
        $previewEnd = $book->preview_end_page;
        return view('admin.books.show', compact('book','previewStart',
        'previewEnd'));
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        $subcategories = $book->category->subcategories;
        return view('admin.books.edit', compact(
            'book',
            'categories',
            'subcategories'
        ));
    }

    public function update(Request $request, Book $book)
    {
        if ($book->status === 'published') {

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
                'string'
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
        $book->update($data);

        return redirect()
            ->route('admin.books.index')
            ->with(
                'success',
                'Livre modifié avec succès.'
            );
    }

    public function destroy(Book $book)
    {

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
            Storage::disk('public')->exists($book->file_path)
        ){

            Storage::disk('public')
                ->delete($book->file_path);

        }

        /* Supprimer le livre */
        // $book->delete();
        \App\Models\Book::destroy($book->id);
        return redirect()
            ->route('admin.books.index')
            ->with(
                'success',
                'Livre supprimé avec succès.'
            );

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


            $imagick = new Imagick();


            $imagick->setResolution(150,150);


            // page PDF (index commence à 0)
            $imagick->readImage(
                $pdfPath.'['.($pageNumber-1).']'
            );


            $imagick->setImageFormat("webp");


            $imagick->setImageCompressionQuality(85);


            $imagick->writeImage(
                $folder.'/page-'.$pageNumber.'.webp'
            );


            $imagick->clear();

            $imagick->destroy();

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
            'subcategory'
        ]);

        // FILTRE PAR STATUT
        if($request->filled('status')){

            $query->where(
                'status',
                $request->status
            );

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
            ->paginate(4)
            ->withQueryString();

        // Statistiques

        $totalBooks = Book::count();


        $pendingBooks = Book::query()->where('status','waiting_review')
            ->count();


        $publishedBooks = Book::query()->where('status','published')
            ->count();


        $reviewBooks = Book::query()->where('status','under_review')
            ->count();



        return view('admin.books.allbooks', compact(
            'books',
            'totalBooks',
            'pendingBooks',
            'publishedBooks',
            'reviewBooks'
        ));
    }

    public function review(Book $book)
    {

        $payment = Payment::query()->where('book_id',$book->id)
            ->where('type','publication')
            ->where('status','success')
            ->first();


        if(!$payment){
            return back()->with(
                'error',
                'Le paiement du dépôt est requis avant la vérification.'
            );

        }



        $book->update([
            'status'=>'under_review'
        ]);


        $book->author->notify(
            new BookUnderReviewNotification($book)
        );

        return redirect()
            ->route('admin.books.show',$book)
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
        ->where('status','rejected')
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
}
