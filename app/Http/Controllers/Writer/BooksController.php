<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Storage;

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
            ->paginate(10)
            ->withQueryString();

        return view(
            'writer.books.index',
            compact('books')
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
            'long_description' => 'required|string',
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
                'max:2048'
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
        ]);


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
            'books/files/ebooks',
            $fileName,
            'public'
        );
        } else {
               $filePath = $file->storeAs(
            'books/files/audios',
            $fileName,
            'public'
        );
        }
     

       $fileSize = $file->getSize();

        Book::create([

            // auteur connecté
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'title' => $request->title,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
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
 
            // statut initial
            'status' => 'draft',

        ]);

        return redirect()
            ->route('writer.books')
            ->with(
                'success',
                'Votre livre a été ajouté avec succès.'
            );
    }

    public function show(Book $book)
    {
        return view('writer.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
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
            ->route('writer.books')
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
            ->route('writer.books')
            ->with(
                'success',
                'Livre supprimé avec succès.'
            );

    }

    public function deposit(Book $book)
    {
        return view(
            'writer.books.deposit',
            compact('book')
        );
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
        if ($book->user_id !== Auth::id()) {
            abort(403);
        }

        $book->update([
            'status' => 'pending_payment'
        ]);

        return back()->with(
            'success',
            'Votre livre est en attente de paiement.'
        );
    }

}
