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
use App\Http\Controllers\AssetReportController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use App\Models\Admin\SubCategory;
use App\Models\ItemLog;
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
        // Show the basic form without serials
        Route::get('/item/create-basic', [ItemController::class, 'createBasic'])->name('item.create.basic');
        Route::post('/item/store-basic', [ItemController::class, 'storeBasic'])->name('item.store.basic');

        Route::get('/item/add-serials', [ItemController::class, 'editSerials'])->name('item.editSerials');
        Route::post('/item/update-serials', [ItemController::class, 'updateSerials'])->name('item.updateSerials');



        Route::resource('dispatch_requests', DispatchRequestController::class);
        Route::post('dispatch_requests/{id}/update-status', [DispatchRequestController::class, 'updateStatus'])->name('dispatch_requests.updateStatus');
        Route::post('items/{item}/update-status', [ItemController::class, 'updateStatus'])->name('items.updateStatus');
        Route::get('dispatch_requests/{id}/print-gate-pass', [DispatchRequestController::class, 'printGatePass'])->name('dispatch_requests.printGatePass');
        Route::put('/dispatch-requests/{id}', [DispatchRequestController::class, 'update'])->name('dispatch-requests.update');
        Route::get('/dispatch-requests', [DispatchRequestController::class, 'index'])->name('dispatch-requests.index');
        Route::get('/dispatch-requests/maintenace', [DispatchRequestController::class, 'maintenance'])->name('dispatch-requests.maintenance');


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

        Route::prefix('serialnumbers')->name('serialnumbers.')->group(function () {
            // Route to list serial numbers for the Employee Devices category
            Route::get('employee-devices', [SerialNumberController::class, 'employeeDevicesIndex'])->name('employee_devices.index');

            // Route to show the form to assign a serial number to a user
            Route::get('{serialNumber}/assign', [SerialNumberController::class, 'assignForm'])->name('assign.form');

            // Route to handle the assignment of a serial number to a user
            Route::post('{serialNumber}/assign', [SerialNumberController::class, 'assign'])->name('assign');
        });

        Route::put('/serialnumbers/{serialNumber}/unassign', [SerialNumberController::class, 'unassignSerialNumber'])->name('serialnumbers.unassign');

        Route::post('/update-password', [UserController::class, 'updatePassword'])->name('update.password');

        Route::get('/reports/assets', [AssetReportController::class, 'index'])->name('reports.assets');

        Route::post('/assets/{item}/dispatch', [AssetReportController::class, 'dispatch'])->name('assets.dispatch');


    }
);


