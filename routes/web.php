<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Home
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/
Route::get('/search',         [SearchController::class, 'index'])->name('search');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

/*
|--------------------------------------------------------------------------
| Blog
|--------------------------------------------------------------------------
*/
Route::middleware('blog.enabled')->prefix('blog')->name('blog.')->group(function () {
    Route::get('/',                         [BlogController::class, 'index'])->name('index');
    Route::get('/kategori/{category:slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{tag:slug}',           [BlogController::class, 'tag'])->name('tag');
    Route::get('/{post:slug}',              [BlogController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Contact
|--------------------------------------------------------------------------
*/
Route::middleware('contact.enabled')->group(function () {
    Route::get('/contact-us',  [ContactController::class, 'show'])->name('contact.show');
    Route::post('/contact-us', [ContactController::class, 'submit'])->name('contact.submit');
});

/*
|--------------------------------------------------------------------------
| Halaman Dinamis — HARUS paling bawah
|--------------------------------------------------------------------------
*/
Route::get('/{path}', [PageController::class, 'show'])
    ->where('path', '(?!admin|livewire|home$|contact-us$).*')
    ->name('page.show');