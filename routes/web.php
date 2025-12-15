<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DebtController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SupplierPriceController;
use App\Http\Controllers\Admin\ForecastController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\SupplierScheduleController;
use App\Http\Controllers\Admin\MembershipDiscountController;
use App\Http\Controllers\Admin\MembershipController as AdminMembership;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Customer\MembershipController as CustomerMembership;
use App\Http\Controllers\Customer\DebtController as CustomerDebtController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Supplier\OrderController as SupplierOrderController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Supplier\ProductController as SupplierProductController;

// Landing page publik
Route::get('/', function () {
    return view('welcome');
})->middleware('redirect.role')->name('landing');

// =======================
// Authentication Routes
// =======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});


// =======================
// Admin Routes
// =======================
Route::prefix('admin')
    ->middleware(['auth','role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Users
        Route::resource('users', UserController::class)
            ->only(['index','store','show','update','destroy']);

        // // Manajemen Roles
        Route::resource('roles', RoleController::class)
            ->only(['index','store','update','destroy']);

        // // Manajemen Permissions
        Route::resource('permissions', PermissionController::class)
            ->only(['index','store','update','destroy']);

        Route::get('schedules', [SupplierScheduleController::class, 'index'])->name('schedules.index');
        Route::post('schedules', [SupplierScheduleController::class, 'store'])->name('schedules.store');

        Route::get('prices', [SupplierPriceController::class, 'index'])->name('prices.index');

             // Manajemen Produk
        Route::resource('products', ProductController::class)
            ->only(['index','store','update','destroy']);
        Route::patch('products/{product}/approve', [ProductController::class, 'approve'])
            ->name('products.approve');
        Route::get('orders', [AdminOrderController::class,'index'])->name('orders.index');
        Route::patch('orders/{order}/accept', [AdminOrderController::class,'accept'])->name('orders.accept');
        Route::patch('orders/{order}/reject', [AdminOrderController::class,'reject'])->name('orders.reject');

            // Membership Management
        Route::get('/membership', [AdminMembership::class,'index'])->name('membership.index');
        Route::post('/membership/{subscription}/approve', [AdminMembership::class,'approve'])->name('membership.approve');
        Route::post('/membership/{subscription}/cancel', [AdminMembership::class,'cancel'])->name('membership.cancel');
        Route::post('/membership/{user}/downgrade', [AdminMembership::class,'downgrade'])->name('membership.downgrade');

        Route::get('/membership_discounts', [MembershipDiscountController::class, 'index'])
        ->name('membership_discounts.index');
        Route::post('/membership_discounts', [MembershipDiscountController::class, 'store'])
            ->name('membership_discounts.store');
        Route::put('/membership_discounts/{membershipDiscount}', [MembershipDiscountController::class, 'update'])
            ->name('membership_discounts.update');
        Route::delete('/membership_discounts/{membershipDiscount}', [MembershipDiscountController::class, 'destroy'])
            ->name('membership_discounts.destroy');

        Route::get('/forecast/{product}/generate', [ForecastController::class, 'generate'])
            ->name('forecast.generate');
        Route::get('/forecast/{product}/dashboard', [ForecastController::class, 'dashboard'])
            ->name('forecast.dashboard');
        Route::get('/forecast/summary', [ForecastController::class, 'summary'])
            ->name('forecast.summary');

        Route::get('debts', [DebtController::class, 'index'])->name('debts.index');
        Route::post('debts', [DebtController::class, 'store'])->name('debts.store');
        Route::patch('debts/{debt}/mark-as-paid', [DebtController::class, 'markAsPaid'])->name('debts.markAsPaid');


        Route::get('reports', [ReportController::class,'index'])
            ->name('reports.index');
        Route::get('reports/pdf', [ReportController::class,'exportPdf'])
            ->name('reports.pdf');
    });

// =======================
// Supplier Routes
// =======================
Route::prefix('supplier')
    ->middleware(['auth','role:supplier'])
    ->name('supplier.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', SupplierProductController::class)
             ->only(['index','store']);
        Route::get('orders', [SupplierOrderController::class,'index'])->name('orders.index');
        Route::patch('orders/{order}/{status}', [SupplierOrderController::class,'update'])->name('orders.update');
        Route::patch('schedules/{schedule}/arrive', [SupplierScheduleController::class, 'arrive'])->name('schedules.arrive');
        Route::get('prices', [SupplierPriceController::class, 'index'])->name('prices.index');
        Route::post('prices', [SupplierPriceController::class, 'store'])->name('prices.store');
    });

// =======================
// Customer Routes
// =======================
Route::prefix('customer')
    ->middleware(['auth','role:customer'])
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('products', [CustomerProductController::class, 'index'])
            ->name('products.index');
        Route::get('orders', [CustomerOrderController::class,'index'])->name('orders.index');
        Route::post('products/whatsapp', [CustomerProductController::class, 'whatsapp'])
            ->name('products.whatsapp');
        Route::post('products/{product}/order', [CustomerOrderController::class,'store'])->name('orders.store');
        Route::get('/membership', [CustomerMembership::class,'index'])->name('membership.index');
        Route::post('/membership/subscribe', [CustomerMembership::class,'subscribe'])->name('membership.subscribe');
        Route::post('/membership/{subscription}/cancel', [CustomerMembership::class,'cancel'])->name('membership.cancel');
        Route::get('debts', [CustomerDebtController::class, 'index'])
            ->name('debts.index');
    });

Route::post('/notifications/read', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.read');

// Admin dapat mengubah status manual jika perlu
Route::patch('admin/schedules/{schedule}/status', [SupplierScheduleController::class, 'updateStatus'])
    ->middleware(['auth','role:admin'])
    ->name('admin.schedules.updateStatus');
