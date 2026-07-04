<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicArticleController;
use App\Http\Controllers\PublicCategoryController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Publik (Pengunjung)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cari', SearchController::class)->name('search');
Route::get('/tentang', [PublicPageController::class, 'about'])->name('about');
Route::get('/berita/{article}', [PublicArticleController::class, 'show'])->name('articles.show');
Route::post('/berita/{article}/komentar', [CommentController::class, 'store'])->name('comments.store');
Route::get('/kategori/{category}', [PublicCategoryController::class, 'show'])->name('categories.show');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Dasbor (ketua & admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:ketua,admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('dasbor')->name('admin.')->group(function () {
        Route::get('pexels/cari', [\App\Http\Controllers\Admin\PexelsController::class, 'search'])->name('pexels.search');

        Route::resource('artikel', AdminArticleController::class)
            ->parameters(['artikel' => 'article'])
            ->names('articles')
            ->except('show');

        Route::resource('kategori', AdminCategoryController::class)
            ->parameters(['kategori' => 'category'])
            ->names('categories')
            ->except('show');

        // Khusus ketua (PRD §3)
        Route::middleware('role:ketua')->group(function () {
            Route::resource('pengguna', AdminUserController::class)
                ->parameters(['pengguna' => 'user'])
                ->names('users')
                ->except('show');
            Route::get('statistik', StatistikController::class)->name('statistik');
        });
    });
});
