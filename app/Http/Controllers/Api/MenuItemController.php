<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(Request $request): JsonResponse {
        $q = MenuItem::with(['product', 'menuCategory'])->orderBy('display_name');
        if ($request->filled('store_id')) { $q->where('store_id', $request->get('store_id')); }
        return ApiResponse::success($q->get());
    }
    public function store(Request $request): JsonResponse { $v = $request->validate(['product_id' => 'nullable|exists:products,id', 'menu_category_id' => 'nullable|exists:menu_categories,id', 'display_name' => 'nullable|string|max:150', 'base_price' => 'nullable|numeric|min:0', 'is_available' => 'boolean', 'store_id' => 'nullable|exists:stores,id']); return ApiResponse::success(MenuItem::create(array_merge($v, ['is_available' => $v['is_available'] ?? true, 'store_id' => $v['store_id'] ?? null])), 'Created', 201); }
    public function show(MenuItem $menu_item): JsonResponse { return ApiResponse::success($menu_item->load(['product', 'menuCategory'])); }
    public function update(Request $request, MenuItem $menu_item): JsonResponse { $v = $request->validate(['product_id' => 'nullable|exists:products,id', 'menu_category_id' => 'nullable|exists:menu_categories,id', 'display_name' => 'nullable|string|max:150', 'base_price' => 'nullable|numeric|min:0', 'is_available' => 'boolean', 'store_id' => 'nullable|exists:stores,id']); $menu_item->update($v); return ApiResponse::success($menu_item->fresh()->load(['product', 'menuCategory'])); }
    public function destroy(MenuItem $menu_item): JsonResponse { $menu_item->delete(); return ApiResponse::success(null, 'Deleted'); }
}
