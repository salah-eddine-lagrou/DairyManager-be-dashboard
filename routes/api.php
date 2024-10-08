<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\ClientCategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientSubcategoryController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSubcategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\TourneController;
use App\Http\Controllers\TourneVendeurController;
use App\Http\Controllers\TvaController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\PriceListNameController;
use App\Http\Controllers\ProductStockStatusController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\EquipementCategoryController;
use App\Http\Controllers\SalesAnalysisController;
use App\Http\Controllers\ProductDiscountController;
use App\Http\Controllers\PriceListProductDetailsController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\DiscountSaleController;
use App\Http\Controllers\ClientPaymentController;
use App\Http\Controllers\OperationHistoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportTypeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DashboardController;

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
// Sync route for Role and Permission
Route::post('/roles/sync-permission', [RoleController::class, 'syncPermissions']);

// * Permissions
Route::get('/permissions', [PermissionController::class, 'index']);
Route::post('/permissions', [PermissionController::class, 'store']);
Route::get('/permissions/{id}', [PermissionController::class, 'show']);
Route::put('/permissions/{id}', [PermissionController::class, 'update']);
Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']);

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
Route::get('/client-categories', [ClientCategoryController::class, 'index']);
Route::get('/client-categories/{id}', [ClientCategoryController::class, 'show']);
Route::post('/client-categories', [ClientCategoryController::class, 'store']);
Route::put('/client-categories/{id}', [ClientCategoryController::class, 'update']);
Route::delete('/client-categories/{id}', [ClientCategoryController::class, 'destroy']);

// * ClientSubcategories
Route::get('/client-subcategories', [ClientSubcategoryController::class, 'index']);
Route::get('/client-subcategories/{id}', [ClientSubcategoryController::class, 'show']);
Route::post('/client-subcategories', [ClientSubcategoryController::class, 'store']);
Route::put('/client-subcategories/{id}', [ClientSubcategoryController::class, 'update']);
Route::delete('/client-subcategories/{id}', [ClientSubcategoryController::class, 'destroy']);

// * ProductCatgories
Route::get('/product-categories', [ProductCategoryController::class, 'index']);
Route::get('/product-categories/{id}', [ProductCategoryController::class, 'show']);
Route::post('/product-categories', [ProductCategoryController::class, 'store']);
Route::put('/product-categories/{id}', [ProductCategoryController::class, 'update']);
Route::delete('/product-categories/{id}', [ProductCategoryController::class, 'destroy']);

// * ProductSubcatgories
Route::get('/product-subcategories', [ProductSubcategoryController::class, 'index']);
Route::get('/product-subcategories/{id}', [ProductSubcategoryController::class, 'show']);
Route::post('/product-subcategories', [ProductSubcategoryController::class, 'store']);
Route::put('/product-subcategories/{id}', [ProductSubcategoryController::class, 'update']);
Route::delete('/product-subcategories/{id}', [ProductSubcategoryController::class, 'destroy']);

// * Tournes ! without store method
Route::get('/tournes', [TourneController::class, 'index']);
Route::get('/tournes/{id}', [TourneController::class, 'show']);
Route::put('/tournes/{id}/inactive', [TourneController::class, 'setInactive']);
Route::put('/tournes/{id}/active', [TourneController::class, 'setActive']);
Route::put('/tournes/{id}', [TourneController::class, 'setActive']);

// * TourneVendeur
Route::get('/tourne-vendeur', [TourneVendeurController::class, 'index']);
Route::post('/tourne-vendeur', [TourneVendeurController::class, 'store']);
Route::get('/tourne-vendeur/{id}', [TourneVendeurController::class, 'show']);
Route::put('/tourne-vendeur/{id}', [TourneVendeurController::class, 'update']);
Route::delete('/tourne-vendeur/{id}', [TourneVendeurController::class, 'destroy']);

// * Clients
// TODO manage routes api urls for roles storeByAdmin and storeByVendeur
Route::get('/clients', [ClientController::class, 'index']);
Route::post('/clients', [ClientController::class, 'store']);
Route::get('/clients/{id}', [ClientController::class, 'show']);
Route::put('/clients/{id}', [ClientController::class, 'update']);
Route::delete('/clients/{id}', [ClientController::class, 'destroy']);
Route::delete('/clients/{id}/active', [ClientController::class, 'setActive']);
Route::delete('/clients/{id}/inactive', [ClientController::class, 'setInactive']);

// * Products
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::delete('/products/{id}/active', [ProductController::class, 'setActive']);
Route::delete('/products/{id}/inactive', [ProductController::class, 'setInactive']);
// Sync route for Product and PriceLists
Route::post('/products/sync-price-list', [ProductController::class, 'syncPriceLists']);

// * Tva
Route::get('/tva', [TvaController::class, 'index']);
Route::post('/tva', [TvaController::class, 'store']);
Route::get('/tva/{id}', [TvaController::class, 'show']);
Route::put('/tva/{id}', [TvaController::class, 'update']);
Route::delete('/tva/{id}', [TvaController::class, 'destroy']);

// * PriceLists
Route::get('/price-list', [PriceListController::class, 'index']);
Route::post('/price-list', [PriceListController::class, 'store']);
Route::get('/price-list/{id}', [PriceListController::class, 'show']);
Route::put('/price-list/{id}', [PriceListController::class, 'update']);
Route::delete('/price-list/{id}', [PriceListController::class, 'destroy']);
// Sync route for PriceList and Products
Route::post('/products/sync-products', [PriceListController::class, 'syncProducts']);

// * PriceListNames
Route::get('/price-list-names', [PriceListNameController::class, 'index']);
Route::post('/price-list-names', [PriceListNameController::class, 'store']);
Route::get('/price-list-names/{id}', [PriceListNameController::class, 'show']);
Route::put('/price-list-names/{id}', [PriceListNameController::class, 'update']);
Route::delete('/price-list-names/{id}', [PriceListNameController::class, 'destroy']);

// * ProductStockStatuses
Route::get('product-stock-statuses', [ProductStockStatusController::class, 'index']);
Route::post('product-stock-statuses', [ProductStockStatusController::class, 'store']);
Route::get('product-stock-statuses/{id}', [ProductStockStatusController::class, 'show']);
Route::put('product-stock-statuses/{id}', [ProductStockStatusController::class, 'update']);
Route::delete('product-stock-statuses/{id}', [ProductStockStatusController::class, 'destroy']);

// * Equipements
Route::get('/equipements', [EquipementController::class, 'index']);
Route::post('/equipements', [EquipementController::class, 'store']);
Route::get('/equipements/{id}', [EquipementController::class, 'show']);
Route::put('/equipements/{id}', [EquipementController::class, 'update']);
Route::delete('/equipements/{id}', [EquipementController::class, 'destroy']);
// Sync route for Clients and Equipements
Route::post('/equipements/sync-clients', [EquipementController::class, 'syncEquipementsClients']);

// * EquipementCategories
Route::get('/equipement-categories', [EquipementCategoryController::class, 'index']);
Route::post('/equipement-categories', [EquipementCategoryController::class, 'store']);
Route::get('/equipement-categories/{id}', [EquipementCategoryController::class, 'show']);
Route::put('/equipement-categories/{id}', [EquipementCategoryController::class, 'update']);
Route::delete('/equipement-categories/{id}', [EquipementCategoryController::class, 'destroy']);

// * SalesAnalysis
Route::get('/sales-analysis', [SalesAnalysisController::class, 'index']);
Route::post('/sales-analysis', [SalesAnalysisController::class, 'store']);
Route::get('/sales-analysis/{id}', [SalesAnalysisController::class, 'show']);
Route::put('/sales-analysis/{id}', [SalesAnalysisController::class, 'update']);
Route::delete('/sales-analysis/{id}', [SalesAnalysisController::class, 'destroy']);

// * ProductDiscounts
Route::get('/product-discounts', [ProductDiscountController::class, 'index']);
Route::post('/product-discounts', [ProductDiscountController::class, 'store']);
Route::get('/product-discounts/{id}', [ProductDiscountController::class, 'show']);
Route::put('/product-discounts/{id}', [ProductDiscountController::class, 'update']);
Route::delete('/product-discounts/{id}', [ProductDiscountController::class, 'destroy']);
// Sync route for ProductDiscounts and Clients
Route::post('/product-discounts/sync-products', [ProductDiscountController::class, 'syncDiscountsProducts']);

// * PriceListProductDetails
Route::get('/price-list-product-details', [PriceListProductDetailsController::class, 'index']);
Route::post('/price-list-product-details', [PriceListProductDetailsController::class, 'store']);
Route::get('/price-list-product-details/{id}', [PriceListProductDetailsController::class, 'show']);
Route::put('/price-list-product-details/{id}', [PriceListProductDetailsController::class, 'update']);
Route::delete('/price-list-product-details/{id}', [PriceListProductDetailsController::class, 'destroy']);

// * DiscountSales
Route::get('/discount-sales', [DiscountSaleController::class, 'index']);
Route::post('/discount-sales', [DiscountSaleController::class, 'store']);
Route::get('/discount-sales/{id}', [DiscountSaleController::class, 'show']);
Route::put('/discount-sales/{id}', [DiscountSaleController::class, 'update']);
Route::delete('/discount-sales/{id}', [DiscountSaleController::class, 'destroy']);

// * ClientPayments
Route::get('/client-payments', [ClientPaymentController::class, 'index']);
Route::post('/client-payments', [ClientPaymentController::class, 'store']);
Route::get('/client-payments/{id}', [ClientPaymentController::class, 'show']);
Route::put('/client-payments/{id}', [ClientPaymentController::class, 'update']);
Route::delete('/client-payments/{id}', [ClientPaymentController::class, 'destroy']);

// * OperationHistories
Route::get('/operations-history', [OperationHistoryController::class, 'index']);
Route::post('/operations-history', [OperationHistoryController::class, 'store']);
Route::get('/operations-history/{id}', [OperationHistoryController::class, 'show']);
Route::put('/operations-history/{id}', [OperationHistoryController::class, 'update']);
Route::delete('/operations-history/{id}', [OperationHistoryController::class, 'destroy']);

// * Reports
Route::get('/reports', [ReportController::class, 'index']);
Route::post('/reports', [ReportController::class, 'store']);
Route::get('/reports/{id}', [ReportController::class, 'show']);
Route::put('/reports/{id}', [ReportController::class, 'update']);
Route::delete('/reports/{id}', [ReportController::class, 'destroy']);

// * ReportTypes
Route::get('/report-types', [ReportTypeController::class, 'index']);
Route::post('/report-types', [ReportTypeController::class, 'store']);
Route::get('/report-types/{id}', [ReportTypeController::class, 'show']);
Route::put('/report-types/{id}', [ReportTypeController::class, 'update']);
Route::delete('/report-types/{id}', [ReportTypeController::class, 'destroy']);

// * Dashboards
Route::get('/dashboards', [DashboardController::class, 'index']);
Route::post('/dashboards', [DashboardController::class, 'store']);
Route::get('/dashboards/{id}', [DashboardController::class, 'show']);
Route::put('/dashboards/{id}', [DashboardController::class, 'update']);
Route::delete('/dashboards/{id}', [DashboardController::class, 'destroy']);

// * Auth
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
Route::post('/scenarios-cases', [\App\Http\Controllers\AuthController::class, 'scenariosCases']);

