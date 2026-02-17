<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (prefix: /api)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(base_path('routes/api/auth.php'));
});
