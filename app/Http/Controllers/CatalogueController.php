<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {

        // $categories = Category::withCount('books')
        //     ->get();

        $categories = Category::with('subcategories')
            ->get();


        $books = Book::with(['author','category','subcategory'])
            ->withAvg('reviews','rating')

            ->when(request('category'), function($query){
                $query->where('category_id', request('category'));
            })

            ->when(request('subcategory'), function($query){
                $query->where('subcategory_id', request('subcategory'));
            })

            ->latest()
            ->paginate(9)
            ->withQueryString();


        // Recherche
        if($request->search){
            $search = $request->search;

            $books->where(function($query) use ($search){
                $query->where('title','like','%'.$search.'%')
                ->orWhereHas('author', function($q) use ($search){
                    $q->where('firstname','like','%'.$search.'%')
                      ->orWhere('lastname','like','%'.$search.'%');
                })

                ->orWhereHas('category', function($q) use ($search){
                    $q->where('name','like','%'.$search.'%');
                });

            });

        }

        if($request->title){
            $books->where('title','like','%'.$request->title.'%');
        }

        // Filtre catégorie
        if($request->category){
            $books->where('category_id',$request->category);
        }

        if($request->price){
            if($request->price == '10-20'){
                $books->whereBetween('price',[10,20]);
            }

            if($request->price == '30-40'){
                $books->whereBetween('price',[30,40]);
            }

            if($request->price == '50+'){
                $books->where('price','>=',50);
            }
        }

        if($request->author){
            $books->where(
                'user_id',
                $request->author
            );
        }

        if($request->rating){
            $books->having(
                'reviews_avg_rating',
                '>=',
                $request->rating
            );
        }

        if($request->type){
            $books->where(
                'type',
                $request->type
            );
        }

        

        $authors = User::whereHas('books')
        ->get();


        return view('books.catalogue', compact(
            'books',
            'categories','authors'
        ));

    }


}
