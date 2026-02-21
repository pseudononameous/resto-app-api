<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WasteLog;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WasteLogController extends Controller
{
    public function index(Request $request): JsonResponse {
        $q = WasteLog::with('product')->orderBy('id', 'desc');
        if ($request->filled('store_id')) { $q->whereHas('product', fn ($q) => $q->where('store_id', $request->get('store_id'))); }
        return ApiResponse::success($q->get());
    }
    public function store(Request $request): JsonResponse {
        $v = $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'required|integer', 'reason' => 'nullable|string|max:150', 'recorded_by' => 'nullable|exists:users,id', 'date' => 'nullable|date']);
        return ApiResponse::success(WasteLog::create($v), 'Created', 201);
    }
    public function show(WasteLog $waste_log): JsonResponse { return ApiResponse::success($waste_log->load('product')); }
    public function update(Request $request, WasteLog $waste_log): JsonResponse { $waste_log->update($request->validate(['product_id' => 'sometimes|exists:products,id', 'quantity' => 'sometimes|integer', 'reason' => 'nullable|string|max:150', 'recorded_by' => 'nullable|exists:users,id', 'date' => 'nullable|date'])); return ApiResponse::success($waste_log->fresh()->load('product')); }
    public function destroy(WasteLog $waste_log): JsonResponse { $waste_log->delete(); return ApiResponse::success(null, 'Deleted'); }
}
