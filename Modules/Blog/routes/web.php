<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;
use Modules\Blog\Http\Controllers\AuthController;
use Modules\Blog\Http\Controllers\BlogCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('blog-panel')->group(function () {

    Route::get('/login', [AuthController::class, 'login'])->name('blog.login');
    Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('blog.authenticate');
    Route::post('/logout', [AuthController::class, 'logout'])->name('blog.logout');
});

Route::prefix('blog-panel')
    ->as('blog.')
    ->middleware(['auth'])
    ->group(function () {

        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/blogs', [BlogController::class, 'list'])->name('list');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::get('/{blog}/show', [BlogController::class, 'show'])->name('show');
        Route::post('/store', [BlogController::class, 'store'])->name('store');
        Route::get('/{blog}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::put('/{blog}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('destroy');
        
        Route::get('/categories', [BlogCategoryController::class, 'index'])->name('categories');
        Route::get('/category/create', [BlogCategoryController::class, 'create'])->name('category.create');
        Route::post('/category/store', [BlogCategoryController::class, 'store'])->name('category.store');
        Route::get('/category/{category}/edit', [BlogCategoryController::class, 'edit'])->name('category.edit');
        Route::put('/category/{category}', [BlogCategoryController::class, 'update'])->name('category.update');
        Route::delete('/category/{category}', [BlogCategoryController::class, 'destroy'])->name('category.destroy');
});
