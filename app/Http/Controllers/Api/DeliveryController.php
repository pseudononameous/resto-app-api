<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(): JsonResponse { return ApiResponse::success(Delivery::with(['order', 'address', 'zone'])->orderBy('id', 'desc')->get()); }
    public function store(Request $request): JsonResponse {
        $v = $request->validate(['order_id' => 'required|exists:orders,id', 'address_id' => 'required|exists:delivery_addresses,id', 'zone_id' => 'nullable|exists:delivery_zones,id', 'rider_id' => 'nullable|exists:users,id', 'status' => 'in:pending,out_for_delivery,delivered,cancelled']);
        return ApiResponse::success(Delivery::create(array_merge($v, ['status' => $v['status'] ?? 'pending'])), 'Created', 201);
    }
    public function show(Delivery $delivery): JsonResponse { return ApiResponse::success($delivery->load(['order', 'address', 'zone'])); }
    public function update(Request $request, Delivery $delivery): JsonResponse { $delivery->update($request->validate(['order_id' => 'sometimes|exists:orders,id', 'address_id' => 'sometimes|exists:delivery_addresses,id', 'zone_id' => 'nullable|exists:delivery_zones,id', 'rider_id' => 'nullable|exists:users,id', 'status' => 'in:pending,out_for_delivery,delivered,cancelled'])); return ApiResponse::success($delivery->fresh()->load(['order', 'address', 'zone'])); }
    public function destroy(Delivery $delivery): JsonResponse { $delivery->delete(); return ApiResponse::success(null, 'Deleted'); }
}
