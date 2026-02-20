<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\MenuCategoryController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\DeliveryZoneController;
use App\Http\Controllers\Api\OrderTypeController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ComboMealController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\KitchenTicketController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\Api\StockBatchController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\WasteLogController;
use App\Http\Controllers\Api\DeliveryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(base_path('routes/api/auth.php'));
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('brands', BrandController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('stores', StoreController::class);
        Route::post('products/import', [ProductController::class, 'import']);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('menu-categories', MenuCategoryController::class);
        Route::apiResource('menu-items', MenuItemController::class);
        Route::apiResource('delivery-zones', DeliveryZoneController::class);
        Route::apiResource('order-types', OrderTypeController::class);
        Route::apiResource('groups', GroupController::class);
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('combo-meals', ComboMealController::class);
        Route::apiResource('carts', CartController::class);
        Route::post('carts/{cart}/items', [CartController::class, 'addItem']);
        Route::delete('carts/{cart}/items/{cart_item}', [CartController::class, 'removeItem'])->scopeBindings();
        Route::post('checkout', [CheckoutController::class, 'store']);
        Route::apiResource('orders', OrderController::class);
        Route::apiResource('kitchen-tickets', KitchenTicketController::class);
        Route::apiResource('stock-movements', StockMovementController::class);
        Route::apiResource('stock-batches', StockBatchController::class);
        Route::apiResource('reservations', ReservationController::class);
        Route::apiResource('waste-logs', WasteLogController::class);
        Route::apiResource('deliveries', DeliveryController::class);
    });
});
