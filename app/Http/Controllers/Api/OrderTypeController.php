<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderType;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
{
    public function index(): JsonResponse { return ApiResponse::success(OrderType::orderBy('type_name')->get()); }
    public function store(Request $request): JsonResponse { $v = $request->validate(['type_name' => 'required|string|max:50']); return ApiResponse::success(OrderType::create($v), 'Created', 201); }
    public function show(OrderType $order_type): JsonResponse { return ApiResponse::success($order_type); }
    public function update(Request $request, OrderType $order_type): JsonResponse { $order_type->update($request->validate(['type_name' => 'sometimes|string|max:50'])); return ApiResponse::success($order_type->fresh()); }
    public function destroy(OrderType $order_type): JsonResponse { $order_type->delete(); return ApiResponse::success(null, 'Deleted'); }
}
