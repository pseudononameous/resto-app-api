<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockBatch;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockBatchController extends Controller
{
    public function index(Request $request): JsonResponse {
        $q = StockBatch::with('product');
        if ($request->filled('product_id')) {
            $q->where('product_id', $request->get('product_id'));
        }
        if ($request->filled('store_id')) { $q->whereHas('product', fn ($q) => $q->where('store_id', $request->get('store_id'))); }
        $q->orderByRaw('CASE WHEN expiry_date IS NULL THEN 1 WHEN expiry_date < CURDATE() THEN 0 ELSE 2 END')->orderBy('expiry_date')->orderBy('id');
        return ApiResponse::success($q->get());
    }
    public function store(Request $request): JsonResponse {
        $v = $request->validate([
            'product_id' => 'required|exists:products,id', 'quantity' => 'required|integer|min:0',
            'supplier' => 'nullable|string|max:150', 'reference_no' => 'nullable|string|max:100',
            'unit_cost' => 'nullable|numeric|min:0', 'storage_location' => 'nullable|string|max:100', 'notes' => 'nullable|string|max:500',
            'prepared_date' => 'nullable|date', 'expiry_date' => 'nullable|date', 'prepared_by' => 'nullable|exists:users,id',
        ]);
        $v['remaining_quantity'] = $v['quantity'];
        $batch = StockBatch::create($v);
        return ApiResponse::success($batch->load('product'), 'Created', 201);
    }
    public function show(StockBatch $stock_batch): JsonResponse { return ApiResponse::success($stock_batch->load('product')); }
    public function update(Request $request, StockBatch $stock_batch): JsonResponse {
        $v = $request->validate([
            'product_id' => 'sometimes|exists:products,id', 'quantity' => 'sometimes|integer|min:0',
            'remaining_quantity' => 'sometimes|integer|min:0', 'supplier' => 'nullable|string|max:150', 'reference_no' => 'nullable|string|max:100',
            'unit_cost' => 'nullable|numeric|min:0', 'storage_location' => 'nullable|string|max:100', 'notes' => 'nullable|string|max:500',
            'prepared_date' => 'nullable|date', 'expiry_date' => 'nullable|date', 'prepared_by' => 'nullable|exists:users,id',
        ]);
        $stock_batch->update($v);
        return ApiResponse::success($stock_batch->fresh()->load('product'));
    }
    public function destroy(StockBatch $stock_batch): JsonResponse { $stock_batch->delete(); return ApiResponse::success(null, 'Deleted'); }
}
