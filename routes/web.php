<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/flash-sale', [HomeController::class, 'flashSale'])->name('flash-sale');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact');

// Shop & Product
Route::get('/shop', [UserController::class, 'shop'])->name('shop');
Route::get('/product/{id}', [UserController::class, 'productDetail'])->name('product.detail');

// Auth (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ADMIN ROUTES (ROLE_ADMIN)
Route::middleware(['auth', 'role:ROLE_ADMIN'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Products
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/products/new', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products/new', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/edit/{id}', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::post('/products/edit/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::post('/products/delete/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');

        // Orders
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [AdminController::class, 'orderDetail'])->name('orders.show');
        Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{id}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');

        // Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'saveSettings'])->name('settings.save');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

// STAFF + ADMIN ROUTES
Route::middleware(['auth', 'role:ROLE_STAFF,ROLE_ADMIN'])
    ->prefix('delivery')
    ->name('delivery.')
    ->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
        Route::get('/', [StaffController::class, 'deliveryOrders'])->name('index');
        Route::get('/orders', [StaffController::class, 'allOrders'])->name('orders');
        Route::get('/order/{id}', [StaffController::class, 'orderDetail'])->name('order.show');
        Route::post('/order/{id}/status', [StaffController::class, 'updateStatus'])->name('order.status');
    });

// CART & CHECKOUT ROUTES (allow guest)
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'cartCount'])->name('cart.count');

Route::get('/checkout', [CartController::class, 'checkoutPage'])->name('checkout');
Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.place');

// AUTHENTICATED USER ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/order/success', [UserController::class, 'orderSuccess'])->name('order.success');
    Route::get('/order/history', [UserController::class, 'orderHistory'])->name('order.history');
    Route::get('/user/orders', [UserController::class, 'userOrders'])->name('user.orders');
    Route::get('/profile', fn() => redirect()->route('profile'))->name('profile.redirect');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/user/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});