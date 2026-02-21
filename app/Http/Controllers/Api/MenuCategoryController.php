<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index(Request $request): JsonResponse {
        $q = MenuCategory::orderBy('name');
        if ($request->filled('store_id')) { $q->where('store_id', $request->get('store_id')); }
        return ApiResponse::success($q->get());
    }
    public function store(Request $request): JsonResponse { $v = $request->validate(['name' => 'required|string|max:100', 'active' => 'boolean', 'store_id' => 'nullable|exists:stores,id']); return ApiResponse::success(MenuCategory::create(['name' => $v['name'], 'active' => $v['active'] ?? true, 'store_id' => $v['store_id'] ?? null]), 'Created', 201); }
    public function show(MenuCategory $menu_category): JsonResponse { return ApiResponse::success($menu_category); }
    public function update(Request $request, MenuCategory $menu_category): JsonResponse { $menu_category->update($request->validate(['name' => 'sometimes|string|max:100', 'active' => 'boolean', 'store_id' => 'nullable|exists:stores,id'])); return ApiResponse::success($menu_category->fresh()); }
    public function destroy(MenuCategory $menu_category): JsonResponse { $menu_category->delete(); return ApiResponse::success(null, 'Deleted'); }
}
