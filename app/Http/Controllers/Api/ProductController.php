<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Product::with(['category', 'brand', 'store'])->orderBy('name');
        if ($request->filled('store_id')) {
            $q->where('store_id', $request->get('store_id'));
        }
        $perPage = (int) $request->get('per_page', 15);
        if ($perPage > 0) {
            $paginator = $q->paginate($perPage);
            return ApiResponse::successWithMeta($paginator->items(), $paginator);
        }
        return ApiResponse::success($q->get());
    }

    public function store(Request $request): JsonResponse
    {
        $v = $request->validate([
            'name' => 'required|string|max:150', 'sku' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0', 'cost_price' => 'nullable|numeric|min:0',
            'qty' => 'integer|min:0', 'reorder_level' => 'integer|min:0',
            'category_id' => 'nullable|exists:categories,id', 'brand_id' => 'nullable|exists:brands,id', 'store_id' => 'nullable|exists:stores,id',
            'availability' => 'boolean',
        ]);
        $p = Product::create(array_merge($v, ['qty' => $v['qty'] ?? 0, 'reorder_level' => $v['reorder_level'] ?? 0, 'availability' => $v['availability'] ?? true]));
        return ApiResponse::success($p->load(['category', 'brand', 'store']), 'Created', 201);
    }

    public function show(Product $product): JsonResponse
    {
        return ApiResponse::success($product->load(['category', 'brand', 'store']));
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $v = $request->validate([
            'name' => 'sometimes|string|max:150', 'sku' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0', 'cost_price' => 'nullable|numeric|min:0',
            'qty' => 'integer|min:0', 'reorder_level' => 'integer|min:0',
            'category_id' => 'nullable|exists:categories,id', 'brand_id' => 'nullable|exists:brands,id', 'store_id' => 'nullable|exists:stores,id',
            'availability' => 'boolean',
        ]);
        $product->update($v);
        return ApiResponse::success($product->fresh()->load(['category', 'brand', 'store']));
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return ApiResponse::success(null, 'Deleted');
    }
}
