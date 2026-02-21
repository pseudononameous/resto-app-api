<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeliveryZoneController extends Controller
{
    public function index(): JsonResponse { return ApiResponse::success(DeliveryZone::orderBy('zone_name')->get()); }
    public function store(Request $request): JsonResponse { $v = $request->validate(['zone_name' => 'nullable|string|max:100', 'delivery_fee' => 'nullable|numeric|min:0']); return ApiResponse::success(DeliveryZone::create($v), 'Created', 201); }
    public function show(DeliveryZone $delivery_zone): JsonResponse { return ApiResponse::success($delivery_zone); }
    public function update(Request $request, DeliveryZone $delivery_zone): JsonResponse { $delivery_zone->update($request->validate(['zone_name' => 'nullable|string|max:100', 'delivery_fee' => 'nullable|numeric|min:0'])); return ApiResponse::success($delivery_zone->fresh()); }
    public function destroy(DeliveryZone $delivery_zone): JsonResponse { $delivery_zone->delete(); return ApiResponse::success(null, 'Deleted'); }
}
