<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $categories = Category::with('subcategories')
            ->withCount('subcategories')
            ->withCount('books')
            ->orderBy('name')
            ->when($request->search, function($query) use ($request){
                $search = $request->search;
                $query->where(function($q) use ($search){

                    // Recherche catégorie
                    $q->where('name','like','%'.$search.'%')

                    // Recherche sous-catégorie
                    ->orWhereHas('subcategories', function($sub) use ($search){

                        $sub->where(
                            'name',
                            'like',
                            '%'.$search.'%'
                        );
                    });
                });
            })

            ->latest()

            ->paginate(8)

            ->withQueryString();

        $totalCategories = Category::count();
        $totalSubcategories = Subcategory::count();
        $totalBooksClassified = Book::whereNotNull('category_id')->count();
        $emptyCategories = Category::doesntHave('books')->count();

        return view('admin.categories.index', compact(
            'categories',
            'totalCategories',
            'totalSubcategories',
            'totalBooksClassified',
            'emptyCategories'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([

            'name'=>'required|string|max:255',

            'subcategories'=>'nullable|array',

            'subcategories.*'=>'nullable|string|max:255'

        ]);



        $category = Category::create([
            'name'=>$request->name,
            'slug'=>Str::slug($request->name)

        ]);




        if($request->filled('subcategories')){
          $subcategories = explode(',', $request->subcategories[0]);
            foreach($subcategories as $subcategory){
                $subcategory = trim($subcategory);
                if($subcategory){
                    $category->subcategories()->create([
                        'name' => $subcategory,
                        'slug' => Str::slug($subcategory)
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Catégorie ajoutée avec succès'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'subcategories'=>'nullable|string'
        ]);

        $category->update([
            'name'=>$request->name,
            'slug'=>Str::slug($request->name)
        ]);

        // Supprimer les anciennes sous-catégories
        $category->subcategories()->delete();

        // Recréer les nouvelles
        if($request->filled('subcategories')){
            $subcategories = explode(',', $request->subcategories);
            foreach($subcategories as $subcategory){
                $subcategory = trim($subcategory);
                if($subcategory){
                    $category->subcategories()->create([
                        'name'=>$subcategory,
                        'slug'=>Str::slug($subcategory)
                    ]);
                }
            }
        }



        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Catégorie modifiée avec succès'
            );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->books()->exists()) {
            return back()->with(
                'error',
                'Impossible de supprimer cette catégorie car elle est utilisée par un ou plusieurs livres.'
            );
        }

        DB::transaction(function () use ($category) {

            $category->subcategories()->delete();

            $category->delete();

        });

        return back()->with(
            'success',
            'Catégorie supprimée avec succès.'
        );
    }
}
