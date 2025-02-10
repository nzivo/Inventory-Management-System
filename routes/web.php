<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Assets\SerialNumberController;
use App\Http\Controllers\DispatchRequestController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use App\Models\Admin\SubCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::middleware(['useradmin'])->group(
    function () {

        Route::resource('items', ItemController::class);
        Route::get("/item-assets", [ItemController::class, 'assets'])->name('asset.assets');
        Route::get('/asset/{id}', [ItemController::class, 'showAsset'])->name('asset.showAsset'); // View a single asset
        Route::get('/asset/{id}/edit', [ItemController::class, 'editAsset'])->name('asset.editAsset'); // Edit asset
        Route::put('/asset/{id}', [ItemController::class, 'updateAsset'])->name('asset.updateAsset'); // Update asset
        Route::delete('/asset/{id}', [ItemController::class, 'destroyAsset'])->name('asset.destroyAsset'); // Delete asset

        Route::get('assets/{item}/add-serial-numbers', [SerialNumberController::class, 'create'])->name('serialnumbers.create');
        Route::post('assets/{item}/store-serial-numbers', [SerialNumberController::class, 'store'])->name('serialnumbers.store');


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

        Route::get('/subcategories/{category_id}', function ($category_id) {
            $subcategories = SubCategory::where('category_id', $category_id)->get();
            return response()->json($subcategories);
        });


        // Route::get();
        // Route::get("assets");
        // Route::get("assets/add", []);
        // Route::get();
    }
);
