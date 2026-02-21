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
        $q = StockBatch::with('product')->orderBy('id', 'desc');
        if ($request->filled('store_id')) { $q->whereHas('product', fn ($q) => $q->where('store_id', $request->get('store_id'))); }
        return ApiResponse::success($q->get());
    }
    public function store(Request $request): JsonResponse {
        $v = $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'required|integer', 'prepared_date' => 'nullable|date', 'expiry_date' => 'nullable|date', 'prepared_by' => 'nullable|exists:users,id']);
        return ApiResponse::success(StockBatch::create($v), 'Created', 201);
    }
    public function show(StockBatch $stock_batch): JsonResponse { return ApiResponse::success($stock_batch->load('product')); }
    public function update(Request $request, StockBatch $stock_batch): JsonResponse { $stock_batch->update($request->validate(['product_id' => 'sometimes|exists:products,id', 'quantity' => 'sometimes|integer', 'prepared_date' => 'nullable|date', 'expiry_date' => 'nullable|date', 'prepared_by' => 'nullable|exists:users,id'])); return ApiResponse::success($stock_batch->fresh()->load('product')); }
    public function destroy(StockBatch $stock_batch): JsonResponse { $stock_batch->delete(); return ApiResponse::success(null, 'Deleted'); }
}
