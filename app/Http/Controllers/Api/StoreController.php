<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(Store::orderBy('name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $v = $request->validate(['name' => 'required|string|max:150', 'active' => 'boolean']);
        $s = Store::create(['name' => $v['name'], 'active' => $v['active'] ?? true]);
        return ApiResponse::success($s, 'Created', 201);
    }

    public function show(Store $store): JsonResponse
    {
        return ApiResponse::success($store);
    }

    public function update(Request $request, Store $store): JsonResponse
    {
        $store->update($request->validate(['name' => 'sometimes|string|max:150', 'active' => 'boolean']));
        return ApiResponse::success($store->fresh());
    }

    public function destroy(Store $store): JsonResponse
    {
        $store->delete();
        return ApiResponse::success(null, 'Deleted');
    }
}
