<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request): JsonResponse {
        $q = StockMovement::with(['product', 'batch'])->orderBy('id', 'desc');
        if ($request->filled('product_id')) {
            $q->where('product_id', $request->get('product_id'));
        }
        if ($request->filled('store_id')) { $q->whereHas('product', fn ($q) => $q->where('store_id', $request->get('store_id'))); }
        return ApiResponse::success($q->get());
    }
    public function store(Request $request): JsonResponse {
        $v = $request->validate([
            'product_id' => 'required|exists:products,id', 'batch_id' => 'nullable|exists:stock_batches,id',
            'movement_type' => 'required|in:prepared,sold,waste,adjustment,purchase', 'quantity' => 'required|integer',
            'reference_id' => 'nullable', 'notes' => 'nullable|string|max:500',
        ]);
        return ApiResponse::success(StockMovement::create($v)->load(['product', 'batch']), 'Created', 201);
    }
    public function show(StockMovement $stock_movement): JsonResponse { return ApiResponse::success($stock_movement->load(['product', 'batch'])); }
    public function update(Request $request, StockMovement $stock_movement): JsonResponse {
        $stock_movement->update($request->validate([
            'product_id' => 'sometimes|exists:products,id', 'batch_id' => 'nullable|exists:stock_batches,id',
            'movement_type' => 'sometimes|in:prepared,sold,waste,adjustment,purchase', 'quantity' => 'sometimes|integer',
            'reference_id' => 'nullable', 'notes' => 'nullable|string|max:500',
        ]));
        return ApiResponse::success($stock_movement->fresh()->load(['product', 'batch']));
    }
    public function destroy(StockMovement $stock_movement): JsonResponse { $stock_movement->delete(); return ApiResponse::success(null, 'Deleted'); }
}
