<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(Brand::orderBy('name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $v = $request->validate(['name' => 'required|string|max:100', 'active' => 'boolean']);
        $b = Brand::create(['name' => $v['name'], 'active' => $v['active'] ?? true]);
        return ApiResponse::success($b, 'Created', 201);
    }

    public function show(Brand $brand): JsonResponse
    {
        return ApiResponse::success($brand);
    }

    public function update(Request $request, Brand $brand): JsonResponse
    {
        $brand->update($request->validate(['name' => 'sometimes|string|max:100', 'active' => 'boolean']));
        return ApiResponse::success($brand->fresh());
    }

    public function destroy(Brand $brand): JsonResponse
    {
        $brand->delete();
        return ApiResponse::success(null, 'Deleted');
    }
}
