<?php

use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\DetailsLivreController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\Writer\DashboardController as WriterDashboardController;
use App\Http\Controllers\Writer\SettingsController as WriterSettingsController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\BookSponsorshipController as AdminBookSponsorshipController;
use App\Http\Controllers\Writer\RevenueController as WriterRevenuesController;
use App\Http\Controllers\Writer\ActivityController as WriterActivityController;
use App\Http\Controllers\SponsorshipController;
use App\Http\Controllers\NotificationSettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SocialProfileController;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Reader\SettingsController as ReaderSettingsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BooksController as AdminBooksController;
use App\Http\Controllers\Writer\BooksController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');
Route::get('/books/{book}', [DetailsLivreController::class, 'index'])
    ->name('books.show');

// Auth routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get(
    '/book-preview/{book}',
    [BooksController::class, 'previewPdf']
)->name('book.preview');

Route::get(
    '/generate-preview/{book}',
    [BooksController::class,'generatePreviewPages']
);

Route::get(
    '/book-preview-page/{book}/{page}',
    [BooksController::class,'previewPage']
)->name('book.preview.page');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {

    $request->fulfill();

    Auth::logout(); // optionnel mais recommandé

    return redirect('/login')->with(
        'success',
        'Votre adresse email a été vérifiée. Connectez-vous pour continuer.'
    );

})->middleware(['auth', 'signed'])->name('verification.verify');



Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Email envoyé !');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// reader routes

Route::prefix('reader')->middleware('auth')->group(function () {

    Route::get('settings', [ReaderSettingsController::class, 'indexReader']);
    Route::post('/notifications/update', [NotificationSettingController::class, 'update'])
        ->name('reader.notifications.update');

    Route::get('/account', function () {
        return view('reader.profile');
    });

    Route::get('/books', function () {
        return view('reader.book');
    });

    Route::get('/wishlist', function () {
        return view('reader.wishlist');
    });

});
Route::get('/reader/account', [AccountController::class, 'account'])
    ->middleware('auth')
    ->name('reader.account');

Route::post('/become-writer', [UserController::class, 'becomeWriter'])
    ->middleware('auth');

Route::put('/reader/account', [UserController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('reader.account.update');

Route::put('/reader/change-password', [UserController::class, 'changePassword'])
    ->middleware('auth')
    ->name('reader.password.update');



// writer routes

Route::prefix('writer') ->middleware(['auth', 'role:writer']) ->group(function () {
        Route::get('/settings', [WriterSettingsController::class, 'index']);
        Route::post('/notifications/update', [NotificationSettingController::class, 'update'])
            ->name('writer.notifications.update');

        Route::get('/dashboard', [WriterDashboardController::class,'index'])
             ->name('writer.dashboard');

        Route::get('/revenues', [WriterRevenuesController::class,'index'])
            ->name('writer.revenues');

        // Livres
        Route::get('/books', [BooksController::class, 'listBooks'])
            ->name('writer.books');

        Route::get('/books/create', [BooksController::class, 'create'])
            ->name('writer.books.create');

        Route::post('/books', [BooksController::class, 'store'])
            ->name('writer.books.store');

        Route::get('/books/{book}',[BooksController::class, 'show']) 
            ->name('writer.books.show');

        Route::get('/books/{book}/edit', [BooksController::class, 'edit'])
            ->name('writer.books.edit');

        Route::put('/books/{book}', [BooksController::class, 'update'])
            ->name('writer.books.update');

        Route::get('/books/{book}/deposit',[BooksController::class, 'deposit'])
            ->name('writer.books.deposit');

        Route::get('/books/{book}/boost',[BooksController::class,'boost'])
           ->name('writer.books.boost');

        Route::post('/books/{book}/boost/share',[BooksController::class,'shareBook'])
           ->name('writer.books.boost.share');

        Route::delete('books/{book}', [BooksController::class, 'destroy'])
           ->name('writer.books.destroy');

        Route::post('/books/{book}/publish', [BooksController::class, 'publish'])
            ->name('writer.books.publish');

        Route::get('/reviews', [ReviewsController::class, 'index'])
            ->name('writer.reviews');

        Route::get('/activities', [WriterActivityController::class,'index'])
            ->middleware('auth')
            ->name('writer.activities');

        Route::delete('/activities/{activity}',[WriterActivityController::class,'destroy'])
            ->middleware('auth')
            ->name('writer.activities.destroy');

        Route::get('/publicite', function () {
            return view('writer.publicite');
        })->name('writer.publicite');

        Route::get('/books/{book}/preview-file', 
            [BooksController::class, 'previewFile']
        )->name('writer.books.preview.file');

        Route::get('/books/{book}/audio', 
            [BooksController::class, 'streamAudio']
        )->name('writer.books.audio');

        Route::get(
                '/books/{book}/sponsor',
                [SponsorshipController::class,'create']
            )->name('writer.books.sponsor');
        
        Route::post(
            '/books/{book}/sponsorship/{plan}',
            [SponsorshipController::class, 'store']
        )->name('writer.sponsorship.store');

        Route::get(
            '/sponsorships/{sponsorship}/payment',
            [SponsorshipController::class, 'payment'])
        ->name('writer.sponsorship.payment');

        Route::post(
            '/books/{book}/resubmit',
            [BooksController::class,'resubmit']
        )
        ->name('writer.books.resubmit');


});

Route::get('/writer/categories/{category}/subcategories',
    [BooksController::class, 'getSubcategories']
)->name('writer.categories.subcategories');

Route::put('/writer/account', [UserController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('writer.account.update');

Route::put('/writer/change-password', [UserController::class, 'changePassword'])
    ->middleware('auth')
    ->name('writer.password.update');


Route::post('/social-profile', [SocialProfileController::class, 'storeOrUpdate'])
    ->name('social.profile.save')
    ->middleware('auth');


// admin routes

Route::prefix('admin') ->middleware(['auth', 'role:admin'])->name('admin.') ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);
        Route::get('/settings', [AdminSettingsController::class, 'index']);
        Route::get('/users/show/{user}', [UserController::class, 'show'])
        ->name('show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');
        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::resource(
            'categories',
            CategoryController::class
        );
        Route::get('/books', [BooksController::class, 'listBooks'])
            ->name('/books');

        Route::get('/books/create', [AdminBooksController::class, 'create'])
            ->name('books.create');

        Route::post('/books', [AdminBooksController::class, 'store'])
            ->name('books.store');

        Route::get('/books', [AdminBooksController::class, 'listBooks'])
            ->name('books.index');

        Route::get('/books/all', [AdminBooksController::class, 'allBooks'])
            ->name('books.all');

        Route::get(
            '/books/{book}/review',
            [AdminBooksController::class,'review']
        )
        ->name('books.review');

        Route::post('/books', [AdminBooksController::class, 'store'])
            ->name('books.store');

         Route::get('/books/editorial-queue',[AdminBooksController::class,'editorialQueue']
        )->name('books.editorial.queue');

        Route::get('/books/{book}',[AdminBooksController::class, 'show']) 
            ->name('books.show');

        Route::get('/books/{book}/preview-file', 
            [AdminBooksController::class, 'previewFile']
        )->name('books.preview.file');

        Route::get('/books/{book}/audio', 
            [AdminBooksController::class, 'streamAudio']
        )->name('books.audio');

        Route::get('/books/{book}/edit', [AdminBooksController::class, 'edit'])
            ->name('books.edit');

        Route::put('/books/{book}', [AdminBooksController::class, 'update'])
            ->name('books.update');
        
        Route::get('/books/{book}/boost',[AdminBooksController::class,'boost'])
           ->name('books.boost');
        
        Route::post('/admin/books/{book}/sponsor',[AdminBookSponsorshipController::class,'sponsor'])
          ->name('books.sponsor');

        Route::post('/books/{book}/boost/share',[AdminBooksController::class,'shareBook'])
           ->name('books.boost.share');

        Route::post('/books/{book}/publish',[AdminBooksController::class,'publish'])
            ->name('books.publish');

        Route::post('/books/{book}/reject',[AdminBooksController::class,'reject'])
           ->name('books.reject');

});


Route::post('/books/{book}/publication-payment',
            [PaymentController::class,'payPublication']
        )
        ->middleware('auth')
        ->name('books.payment.publication');

Route::put('/admin/change-password', [UserController::class, 'changePassword'])
    ->middleware('auth')
    ->name('admin.password.update');

Route::put('/admin/account', [UserController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('admin.account.update');

Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware('auth')
    ->name('admin.users');


    // routes communes
    Route::middleware('auth')->group(function(){

    Route::delete('/notifications/clear',
        [NotificationController::class,'clear']
    )->name('notifications.clear');

});