<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\ClientCategoryController;
use App\Http\Controllers\ClientSubcategoryController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductSubcategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ZoneController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// * Units
Route::get('/units', [UnitController::class, 'index']);
Route::get('/units/{id}', [UnitController::class, 'show']);
Route::post('/units', [UnitController::class, 'store']);
Route::put('/units/{id}', [UnitController::class, 'update']);
Route::delete('/units/{id}', [UnitController::class, 'destroy']);

// * Agencies
Route::get('/agencies', [AgencyController::class, 'index']);
Route::get('/agencies/{id}', [AgencyController::class, 'show']);
Route::post('/agencies', [AgencyController::class, 'store']);
Route::put('/agencies/{id}', [AgencyController::class, 'update']);
Route::delete('/agencies/{id}', [AgencyController::class, 'destroy']);
Route::put('/agencies/{id}/inactive', [AgencyController::class, 'setInactive']);
Route::put('/agencies/{id}/active', [AgencyController::class, 'setActive']);

// * Users
Route::post('/users/admin', [UserController::class, 'storeAdmin']);
Route::post('/users/vendeur', [UserController::class, 'storeVendeur']);
Route::post('/users/responsable', [UserController::class, 'storeResponsable']);
Route::post('/users/magasinier', [UserController::class, 'storeMagasinier']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users', [UserController::class, 'index']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::put('/users/{id}/inactive', [UserController::class, 'setInactive']);
Route::put('/users/{id}/active', [UserController::class, 'setActive']);

// * Roles
Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::put('/roles/{id}', [RoleController::class, 'update']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

// * Warehouses
Route::get('/warehouses', [WarehouseController::class, 'index']);
Route::get('/warehouses/{id}', [WarehouseController::class, 'show']);
Route::post('/warehouses', [WarehouseController::class, 'store']);
Route::put('/warehouses/{id}', [WarehouseController::class, 'update']);
Route::delete('/warehouses/{id}', [WarehouseController::class, 'destroy']);
Route::put('/warehouses/{id}/inactive', [WarehouseController::class, 'setInactive']);
Route::put('/warehouses/{id}/active', [WarehouseController::class, 'setActive']);

// * Zones
Route::get('/zones', [ZoneController::class, 'index']);
Route::get('/zones/{id}', [ZoneController::class, 'show']);
Route::post('/zones', [ZoneController::class, 'store']);
Route::put('/zones/{id}', [ZoneController::class, 'update']);
Route::delete('/zones/{id}', [ZoneController::class, 'destroy']);

// * Sectors
Route::get('/sectors', [SectorController::class, 'index']);
Route::get('/sectors/{id}', [SectorController::class, 'show']);
Route::post('/sectors', [SectorController::class, 'store']);
Route::put('/sectors/{id}', [SectorController::class, 'update']);
Route::delete('/sectors/{id}', [SectorController::class, 'destroy']);

// * ClientCategories
Route::get('client-categories', [ClientCategoryController::class, 'index']);
Route::get('client-categories/{id}', [ClientCategoryController::class, 'show']);
Route::post('client-categories', [ClientCategoryController::class, 'store']);
Route::put('client-categories/{id}', [ClientCategoryController::class, 'update']);
Route::delete('client-categories/{id}', [ClientCategoryController::class, 'destroy']);

// * ClientSubcategories
Route::get('client-subcategories', [ClientSubcategoryController::class, 'index']);
Route::get('client-subcategories/{id}', [ClientSubcategoryController::class, 'show']);
Route::post('client-subcategories', [ClientSubcategoryController::class, 'store']);
Route::put('client-subcategories/{id}', [ClientSubcategoryController::class, 'update']);
Route::delete('client-subcategories/{id}', [ClientSubcategoryController::class, 'destroy']);

// * ProductCatgories
Route::get('product-categories', [ProductCategoryController::class, 'index']);
Route::get('product-categories/{id}', [ProductCategoryController::class, 'show']);
Route::post('product-categories', [ProductCategoryController::class, 'store']);
Route::put('product-categories/{id}', [ProductCategoryController::class, 'update']);
Route::delete('product-categories/{id}', [ProductCategoryController::class, 'destroy']);

// * ProductSubcatgories
Route::get('product-subcategories', [ProductSubcategoryController::class, 'index']);
Route::get('product-subcategories/{id}', [ProductSubcategoryController::class, 'show']);
Route::post('product-subcategories', [ProductSubcategoryController::class, 'store']);
Route::put('product-subcategories/{id}', [ProductSubcategoryController::class, 'update']);
Route::delete('product-subcategories/{id}', [ProductSubcategoryController::class, 'destroy']);

