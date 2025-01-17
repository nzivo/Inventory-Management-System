<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\DispatchRequestController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::middleware(['useradmin'])->group(
    function () {

        Route::resource('items', ItemController::class);
        Route::resource('dispatch_requests', DispatchRequestController::class);
        Route::post('dispatch_requests/{id}/update-status', [DispatchRequestController::class, 'updateStatus'])->name('dispatch_requests.updateStatus');
        Route::post('items/{item}/update-status', [ItemController::class, 'updateStatus'])->name('items.updateStatus');
        Route::get('dispatch_requests/{id}/print-gate-pass', [DispatchRequestController::class, 'printGatePass'])->name('dispatch_requests.printGatePass');



        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::resource('branches', BranchController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('subcategories', SubCategoryController::class);
        Route::resource('users', UserController::class);
        Route::get('/profile', [UserController::class, 'profile'])->name('home');
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('companies', CompanyController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('designations', DesignationController::class);


        // Route::get();
        // Route::get("assets");
        // Route::get("assets/add", []);
        // Route::get();
    }
);
