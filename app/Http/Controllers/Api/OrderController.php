<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Order::with(['orderType', 'customer', 'user', 'store', 'items.menuItem', 'delivery', 'kitchenTicket'])->orderBy('id', 'desc');
        if ($request->filled('store_id')) {
            $q->where('store_id', $request->get('store_id'));
        }
        if ($request->filled('status')) {
            $q->where('status', $request->get('status'));
        }
        return ApiResponse::success($q->get());
    }

    public function store(Request $request): JsonResponse
    {
        $v = $request->validate([
            'bill_no' => 'nullable|string|max:50',
            'order_type_id' => 'required|exists:order_types,id',
            'reservation_id' => 'nullable',
            'customer_id' => 'nullable|exists:customers,id',
            'date_time' => 'nullable|date',
            'net_amount' => 'nullable|numeric|min:0',
            'paid_status' => 'boolean',
            'status' => 'nullable|string|max:30',
            'user_id' => 'nullable|exists:users,id',
            'store_id' => 'nullable|exists:stores,id',
        ]);
        $v['status'] = $v['status'] ?? 'pending';
        return ApiResponse::success(Order::create(array_merge($v, ['paid_status' => $v['paid_status'] ?? false, 'store_id' => $v['store_id'] ?? null])), 'Created', 201);
    }

    public function show(Order $order): JsonResponse
    {
        return ApiResponse::success($order->load(['orderType', 'customer', 'user', 'store', 'items.menuItem', 'delivery', 'kitchenTicket']));
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $order->update($request->validate([
            'bill_no' => 'nullable|string|max:50',
            'order_type_id' => 'sometimes|exists:order_types,id',
            'reservation_id' => 'nullable',
            'customer_id' => 'nullable|exists:customers,id',
            'date_time' => 'nullable|date',
            'net_amount' => 'nullable|numeric|min:0',
            'paid_status' => 'boolean',
            'status' => 'nullable|string|in:pending,confirmed,preparing,ready,delivered,cancelled',
            'user_id' => 'nullable|exists:users,id',
            'store_id' => 'nullable|exists:stores,id',
        ]));
        return ApiResponse::success($order->fresh()->load(['orderType', 'customer', 'user', 'store', 'items.menuItem', 'delivery', 'kitchenTicket']));
    }
    public function destroy(Order $order): JsonResponse { $order->delete(); return ApiResponse::success(null, 'Deleted'); }
}
