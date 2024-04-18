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

Route::get('/dashboard', function () {

    $user = Auth::user();

    if ($user->hasAnyRole('admin', 'subadmin')) {
        return view('dashboard');
    } else {
        $products = Product::with('images')->paginate(10);
        return view('user.user-dashboard', compact('products'));
    }

})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {

    Route::get('/product-detail-page/{id}', [UserController::class, 'detail'])->name('product-detail-page');
    Route::post('/store-comment', [UserController::class, 'storeComment'])->name('store-comment');
    Route::post('/store-review', [UserController::class, 'storeReview'])->name('store-review');

});


Route::middleware(['auth', 'role:admin|subadmin'])->group(function () {

    Route::get('/index-category', [CategoryController::class, 'index'])->name('indexCategory');
    Route::delete('/delete-category/{id}', [CategoryController::class, 'destroy'])->name('deleteCategory');
    Route::get('/edit-category', [CategoryController::class, 'edit'])->name('editCategory');
    Route::post('/store-category', [CategoryController::class, 'store'])->name('storeCategory');
    Route::post('/update-category', [CategoryController::class, 'update'])->name('updateCategory');

    Route::controller(ProductController::class)->group(function () {
        Route::get('index-product', 'index')->name('indexProduct');
        Route::get('create-product', 'create')->name('createProduct');
        Route::post('store-product', 'store')->name('storeProduct');
        Route::get('edit-product/{id}', 'edit')->name('editProduct');
        Route::get('show-product/{id}', 'show')->name('showProduct');
        Route::get('detail-product/{id}', 'show')->name('detailProduct');
        Route::post('update-product', 'update')->name('updateProduct');
        Route::delete('delete-product/{id}', 'destroy')->name('deleteProduct');
    });
});

require __DIR__ . '/auth.php';
