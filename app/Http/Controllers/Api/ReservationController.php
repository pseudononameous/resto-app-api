<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request): JsonResponse {
        $q = Reservation::with(['customer', 'store'])->orderBy('reservation_date', 'desc')->orderBy('reservation_time', 'desc');
        if ($request->filled('store_id')) { $q->where('store_id', $request->get('store_id')); }
        return ApiResponse::success($q->get());
    }
    public function store(Request $request): JsonResponse {
        $v = $request->validate(['reservation_code' => 'nullable|string|max:50', 'customer_id' => 'nullable|exists:customers,id', 'guest_name' => 'nullable|string|max:150', 'phone' => 'nullable|string|max:50', 'party_size' => 'integer|min:1', 'reservation_date' => 'required|date', 'reservation_time' => 'required', 'status' => 'in:pending,confirmed,seated,completed,cancelled,no_show', 'created_by' => 'nullable|exists:users,id', 'store_id' => 'nullable|exists:stores,id']);
        return ApiResponse::success(Reservation::create(array_merge($v, ['party_size' => $v['party_size'] ?? 1, 'status' => $v['status'] ?? 'pending', 'store_id' => $v['store_id'] ?? null])), 'Created', 201);
    }
    public function show(Reservation $reservation): JsonResponse { return ApiResponse::success($reservation->load('customer')); }
    public function update(Request $request, Reservation $reservation): JsonResponse { $reservation->update($request->validate(['reservation_code' => 'nullable|string|max:50', 'customer_id' => 'nullable|exists:customers,id', 'guest_name' => 'nullable|string|max:150', 'phone' => 'nullable|string|max:50', 'party_size' => 'integer|min:1', 'reservation_date' => 'sometimes|date', 'reservation_time' => 'sometimes', 'status' => 'in:pending,confirmed,seated,completed,cancelled,no_show', 'created_by' => 'nullable|exists:users,id', 'store_id' => 'nullable|exists:stores,id'])); return ApiResponse::success($reservation->fresh()->load(['customer', 'store'])); }
    public function destroy(Reservation $reservation): JsonResponse { $reservation->delete(); return ApiResponse::success(null, 'Deleted'); }
}
