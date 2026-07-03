<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\DetailsLivreController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');
Route::get('/detaillivre', [DetailsLivreController::class, 'index'])->name('detail');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::get('/book', [BookController::class, 'index'])->name('book');