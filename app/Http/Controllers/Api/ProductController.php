<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Product::with(['category', 'brand', 'store'])->orderBy('name');
        if ($request->filled('store_id')) {
            $q->where('store_id', $request->get('store_id'));
        }
        if ($request->filled('category_id')) {
            $q->where('category_id', $request->get('category_id'));
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

    public function import(Request $request): JsonResponse
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt|max:10240']);
        $file = $request->file('file');
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        if (empty($rows)) {
            return ApiResponse::success(['created' => 0, 'errors' => []], 'No rows to import');
        }
        $header = array_map('trim', array_map('strtolower', $rows[0]));
        $created = 0;
        $errors = [];
        foreach (array_slice($rows, 1) as $i => $row) {
            $assoc = array_combine($header, array_pad($row, count($header), null));
            if ($assoc === false) {
                continue;
            }
            $data = [
                'name' => $assoc['name'] ?? $assoc['product_name'] ?? '',
                'sku' => $assoc['sku'] ?? null,
                'price' => isset($assoc['price']) ? (is_numeric($assoc['price']) ? (float) $assoc['price'] : null) : null,
                'qty' => isset($assoc['qty']) ? (int) $assoc['qty'] : 0,
                'category_id' => isset($assoc['category_id']) && $assoc['category_id'] !== '' ? (int) $assoc['category_id'] : null,
                'brand_id' => isset($assoc['brand_id']) && $assoc['brand_id'] !== '' ? (int) $assoc['brand_id'] : null,
                'store_id' => $request->get('store_id') ?: (isset($assoc['store_id']) && $assoc['store_id'] !== '' ? (int) $assoc['store_id'] : null),
                'availability' => ! isset($assoc['availability']) || $assoc['availability'] === '' || in_array(strtolower((string) $assoc['availability']), ['1', 'true', 'yes'], true),
            ];
            $v = Validator::make($data, [
                'name' => 'required|string|max:150',
                'sku' => 'nullable|string|max:50',
                'price' => 'nullable|numeric|min:0',
                'qty' => 'integer|min:0',
                'category_id' => 'nullable|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'store_id' => 'nullable|exists:stores,id',
            ]);
            if ($v->fails()) {
                $errors[] = ['row' => $i + 2, 'message' => $v->errors()->first()];
                continue;
            }
            Product::create(array_merge($data, ['reorder_level' => 0]));
            $created++;
        }
        return ApiResponse::success(['created' => $created, 'errors' => $errors], "Imported {$created} product(s).");
    }
}
