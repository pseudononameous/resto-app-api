<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KitchenTicket;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KitchenTicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = KitchenTicket::with('order.items.menuItem.ingredients.product')->orderBy('id', 'desc');
        if ($request->filled('status')) {
            $q->where('status', $request->get('status'));
        }
        if ($request->filled('order_id')) {
            $q->where('order_id', $request->get('order_id'));
        }
        return ApiResponse::success($q->get());
    }

    public function store(Request $request): JsonResponse
    {
        $v = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'ticket_number' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:30',
        ]);
        $v['ticket_number'] = $v['ticket_number'] ?? 'KT-' . $v['order_id'] . '-' . rand(100, 999);
        $v['status'] = $v['status'] ?? 'pending';
        return ApiResponse::success(KitchenTicket::create($v)->load('order'), 'Created', 201);
    }

    public function show(KitchenTicket $kitchen_ticket): JsonResponse
    {
        return ApiResponse::success($kitchen_ticket->load(['order.items.menuItem.ingredients.product']));
    }

    public function update(Request $request, KitchenTicket $kitchen_ticket): JsonResponse
    {
        $v = $request->validate([
            'ticket_number' => 'sometimes|string|max:50',
            'status' => 'sometimes|string|in:pending,in_preparation,ready,served',
        ]);
        $kitchen_ticket->update($v);
        if (!empty($v['status'])) {
            $kitchen_ticket->order->update(['status' => $v['status'] === 'served' ? 'ready' : $v['status']]);
        }
        return ApiResponse::success($kitchen_ticket->fresh()->load('order'));
    }

    public function destroy(KitchenTicket $kitchen_ticket): JsonResponse
    {
        $kitchen_ticket->delete();
        return ApiResponse::success(null, 'Deleted');
    }
}
