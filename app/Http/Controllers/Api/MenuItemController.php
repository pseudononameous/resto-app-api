<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuItemIngredient;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = MenuItem::with(['menuCategory', 'ingredients.product'])->orderBy('display_name');
        if ($request->filled('store_id')) {
            $q->where('store_id', $request->get('store_id'));
        }
        return ApiResponse::success($q->get());
    }

    public function store(Request $request): JsonResponse
    {
        $v = $request->validate([
            'menu_category_id' => 'nullable|exists:menu_categories,id',
            'display_name' => 'nullable|string|max:150',
            'base_price' => 'nullable|numeric|min:0',
            'image_path' => 'nullable|string|max:500',
            'is_available' => 'boolean',
            'store_id' => 'nullable|exists:stores,id',
            'ingredients' => 'nullable|array',
            'ingredients.*.product_id' => 'required_with:ingredients|exists:products,id',
            'ingredients.*.quantity_per_serving' => 'nullable|numeric|min:0',
        ]);
        $payload = [
            'menu_category_id' => $v['menu_category_id'] ?? null,
            'display_name' => $v['display_name'] ?? null,
            'base_price' => $v['base_price'] ?? null,
            'image_path' => $v['image_path'] ?? null,
            'is_available' => $v['is_available'] ?? true,
            'store_id' => $v['store_id'] ?? null,
        ];
        $item = MenuItem::create($payload);
        $this->syncIngredients($item, $v['ingredients'] ?? []);
        return ApiResponse::success($item->fresh()->load(['menuCategory', 'ingredients.product']), 'Created', 201);
    }

    public function show(MenuItem $menu_item): JsonResponse
    {
        return ApiResponse::success($menu_item->load(['menuCategory', 'ingredients.product']));
    }

    public function update(Request $request, MenuItem $menu_item): JsonResponse
    {
        $v = $request->validate([
            'menu_category_id' => 'nullable|exists:menu_categories,id',
            'display_name' => 'nullable|string|max:150',
            'base_price' => 'nullable|numeric|min:0',
            'image_path' => 'nullable|string|max:500',
            'is_available' => 'boolean',
            'store_id' => 'nullable|exists:stores,id',
            'ingredients' => 'nullable|array',
            'ingredients.*.product_id' => 'required_with:ingredients|exists:products,id',
            'ingredients.*.quantity_per_serving' => 'nullable|numeric|min:0',
        ]);
        $menu_item->update([
            'menu_category_id' => $v['menu_category_id'] ?? $menu_item->menu_category_id,
            'display_name' => $v['display_name'] ?? $menu_item->display_name,
            'base_price' => array_key_exists('base_price', $v) ? $v['base_price'] : $menu_item->base_price,
            'image_path' => array_key_exists('image_path', $v) ? $v['image_path'] : $menu_item->image_path,
            'is_available' => $v['is_available'] ?? $menu_item->is_available,
            'store_id' => array_key_exists('store_id', $v) ? $v['store_id'] : $menu_item->store_id,
        ]);
        if (array_key_exists('ingredients', $v)) {
            $this->syncIngredients($menu_item, $v['ingredients']);
        }
        return ApiResponse::success($menu_item->fresh()->load(['menuCategory', 'ingredients.product']));
    }

    public function destroy(MenuItem $menu_item): JsonResponse
    {
        $menu_item->delete();
        return ApiResponse::success(null, 'Deleted');
    }

    private function syncIngredients(MenuItem $item, array $ingredients): void
    {
        MenuItemIngredient::where('menu_item_id', $item->id)->delete();
        foreach ($ingredients as $row) {
            MenuItemIngredient::create([
                'menu_item_id' => $item->id,
                'product_id' => (int) $row['product_id'],
                'quantity_per_serving' => (float) ($row['quantity_per_serving'] ?? 1),
            ]);
        }
    }
}
