<?php

use App\Http\Controllers\Api\AppPreviewController;
use App\Http\Controllers\Api\BillingSettingsController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CourierSettingsController;
use App\Http\Controllers\Api\IntegrationsSettingsController;
use App\Http\Controllers\Api\NotificationSettingsController;
use App\Http\Controllers\Api\PaymentMethodSettingsController;
use App\Http\Controllers\Api\PayoutSettingsController;
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
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\TaxSettingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(base_path('routes/api/auth.php'));

    // App Preview (Turn your website into an app) — public, uses OpenAI like intellect-edge
    Route::post('app-preview', [AppPreviewController::class, 'store']);

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
        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::put('roles/{role}/permissions', [RoleController::class, 'syncPermissions']);
        Route::get('permissions', [PermissionController::class, 'index']);

        Route::prefix('settings')->group(function () {
            Route::get('integrations', [IntegrationsSettingsController::class, 'show']);
            Route::put('integrations', [IntegrationsSettingsController::class, 'update']);

            Route::get('notifications', [NotificationSettingsController::class, 'show']);
            Route::put('notifications', [NotificationSettingsController::class, 'update']);

            Route::get('payment-methods', [PaymentMethodSettingsController::class, 'show']);
            Route::put('payment-methods', [PaymentMethodSettingsController::class, 'update']);

            Route::get('payouts', [PayoutSettingsController::class, 'show']);
            Route::put('payouts', [PayoutSettingsController::class, 'update']);

            Route::get('tax', [TaxSettingsController::class, 'show']);
            Route::put('tax', [TaxSettingsController::class, 'update']);

            Route::get('billing', [BillingSettingsController::class, 'show']);
            Route::put('billing', [BillingSettingsController::class, 'update']);

            Route::get('courier', [CourierSettingsController::class, 'show']);
            Route::put('courier', [CourierSettingsController::class, 'update']);
        });
    });
});
