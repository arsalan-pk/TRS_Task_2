<?php

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [UserController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/products/{id}', [UserController::class, 'productsShow'])->name('products.show');
    Route::post('/comments', [UserController::class, 'commentsStore'])->name('comments.store');
    Route::post('/reviews', [UserController::class, 'reviewsStore'])->name('reviews.store');
});

Route::middleware(['auth', 'role:admin|subadmin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::get('product/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('products/{category}/category', [ProductController::class, 'show'])->name('products.category.show');
});

require __DIR__ . '/auth.php';
