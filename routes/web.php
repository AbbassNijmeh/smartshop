<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\EnsureIsDelivery;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AllergyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\IngredientController;

Auth::routes();
Route::get('/', [HomeController::class, 'showWelcome'])->name('home');

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    //make admin route
    Route::get('/admin', [HomeController::class, 'showDashboard'])->name('dashboard.index');
    Route::get('/admin/orders', [OrderController::class, 'Adminindex'])->name('orders.index');
    Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/monthly-data', [OrderController::class, 'monthlyData']);

    //make show order route
    Route::get('/admin/admin-orders/{id}', [OrderController::class, 'AdminShow'])->name('admin.orders.show');
    Route::post('/admin/sendToDelivery', [OrderController::class, 'sendToDelivery'])->name('admin.orders.sendToDelivery');

    // make products routes
    Route::get('/admin/products', [ProductController::class, 'Adminindex'])->name('products.index');
    Route::get('/admin/products/{id}', [ProductController::class, 'AdminShow'])->name('products.show');
    Route::get('admin/products/create/newproduct', [ProductController::class, 'create'])->name('products.create');
    Route::post('admin/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('admin/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('admin/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('admin/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('admin/products/restock', [ProductController::class, 'restock'])->name('products.restock');
    Route::delete('products/delete-pic', [ProductController::class, 'deletePic'])->name('products.deletePic');

    // make categories route
    Route::get('/admin/categories', [CategoryController::class, 'Adminindex'])->name('categories.index');
    Route::post('admin/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    //make users route
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}/destroy', [UserController::class, 'destroy'])->name('users.destroy');

    //make payments route
    Route::get('/admin/payments', [PaymentController::class, 'Adminindex'])->name('payments.index');

    //make reviews route
    Route::get('/admin/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/admin/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::delete('/admin/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    //setting
    Route::get('/admin/settings', [SettingController::class, 'show'])->name('settings.show');
    Route::post('/admin/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::prefix('admin/allergies')->group(function () {
        Route::get('/', [AllergyController::class, 'index'])->name('allergies.index'); // List all allergies
        Route::post('/store', [AllergyController::class, 'store'])->name('allergies.store'); // Store new allergy
        Route::get('/{allergy}/edit', [AllergyController::class, 'edit'])->name('allergies.edit'); // Edit modal
        Route::put('/{allergy}/update', [AllergyController::class, 'update'])->name('allergies.update'); // Update allergy
        Route::delete('/{allergy}', [AllergyController::class, 'destroy'])->name('allergies.destroy'); // Delete allergy
    });

    // Ingredient Routes
    Route::prefix('admin/ingredients')->group(function () {
        Route::get('/', [IngredientController::class, 'index'])->name('ingredients.index'); // List all ingredients
        Route::post('/store', [IngredientController::class, 'store'])->name('ingredients.store'); // Store new ingredient
        Route::get('/{ingredient}/edit', [IngredientController::class, 'edit'])->name('ingredients.edit'); // Edit modal
        Route::put('/{ingredient}/update', [IngredientController::class, 'update'])->name('ingredients.update'); // Update ingredient
        Route::delete('/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy'); // Delete ingredient
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/products', [HomeController::class, 'Products'])->name('products');
    Route::get('/filtered-products', [HomeController::class, 'showFilteredProducts'])->name('filtered.products');
    Route::get('/product/{id}', [HomeController::class, 'showProduct'])->name('product.show');
    Route::post('/products/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::post('/wishlist/{id}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    Route::delete('/wishlist/{id}/remove', [WishlistController::class, 'destroy'])->name('wishlist.remove');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{id}/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [PaymentController::class, 'show'])->name('checkout');
    Route::post('/checkout/process', [PaymentController::class, 'process'])->name('checkout.process');
    Route::get('/allergies', [AllergyController::class, 'userAllergies'])->name('user.allergies');
    Route::post('/allergies/update', [AllergyController::class, 'updateUserAllergies'])->name('user.allergies.update');
    Route::get('/my-orders', [OrderController::class, 'userHistory'])->name('user.orderHistory');
    Route::get('/orders/{order}', [OrderController::class, 'userShow'])->name('orders.show');
    Route::get('/profile', [HomeController::class, 'showUserProfile'])->name('profile.show');
    Route::put('/profile/update', [HomeController::class, 'userProfileUpdate'])->name('profile.update');
});
Route::get('api/product-by-barcode', [ProductController::class, 'getProductByBarcode']);
//delivery routes with middle ware
Route::middleware(['auth', EnsureIsDelivery::class])->group(function () {
    Route::get('delivery', [DeliveryController::class, 'index'])->name('delivery.index');
    Route::post('/orders/order/delivery/completed', [DeliveryController::class, 'updateStatus'])->name('delivery.completed');
});

use App\Http\Controllers\RecommendationController;
Route::get('/recommend/upsell/{product}', [RecommendationController::class, 'recommendUpsell']);
Route::get('/recommend/search', [RecommendationController::class, 'search']);
Route::get('/recombee/sync-all-products', [RecommendationController::class, 'syncAllProducts']);
