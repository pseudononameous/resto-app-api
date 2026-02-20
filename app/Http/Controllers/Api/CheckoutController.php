<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Delivery;
use App\Models\KitchenTicket;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockBatch;
use App\Models\StockMovement;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $v = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'order_type_id' => 'required|exists:order_types,id',
            'customer_id' => 'nullable|exists:customers,id',
            'store_id' => 'nullable|exists:stores,id',
            'address_id' => 'nullable|exists:delivery_addresses,id',
            'zone_id' => 'nullable|exists:delivery_zones,id',
        ]);

        $cart = Cart::with(['items.menuItem.ingredients'])->findOrFail($v['cart_id']);
        if ($cart->status !== 'active') {
            return ApiResponse::error('Cart already checked out or cancelled', 422);
        }
        if ($cart->items->isEmpty()) {
            return ApiResponse::error('Cart is empty', 422);
        }

        $orderTypeId = (int) $v['order_type_id'];
        $storeId = isset($v['store_id']) ? (int) $v['store_id'] : $cart->store_id;
        $userId = $request->user()?->id;
        $billNo = 'ORD-' . strtoupper(Str::random(6));

        $netAmount = (float) $cart->total;

        $order = Order::create([
            'bill_no' => $billNo,
            'order_type_id' => $orderTypeId,
            'customer_id' => $v['customer_id'] ?? $cart->customer_id,
            'date_time' => now(),
            'net_amount' => $netAmount,
            'paid_status' => false,
            'status' => 'pending',
            'user_id' => $userId,
            'store_id' => $storeId,
        ]);

        foreach ($cart->items as $line) {
            if (!$line->menu_item_id) {
                continue;
            }
            $menuItem = $line->menuItem;
            $price = (float) ($menuItem->base_price ?? 0);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => null,
                'menu_item_id' => $line->menu_item_id,
                'qty' => $line->quantity,
                'amount' => $price * $line->quantity,
            ]);

            foreach ($menuItem->ingredients ?? [] as $ing) {
                $toDeduct = (float) $ing->quantity_per_serving * $line->quantity;
                $qtyInt = (int) ceil($toDeduct);
                if ($qtyInt <= 0) {
                    continue;
                }
                $batches = StockBatch::where('product_id', $ing->product_id)
                    ->where('remaining_quantity', '>', 0)
                    ->orderBy('expiry_date')
                    ->get();
                $deducted = 0;
                $batchId = null;
                foreach ($batches as $batch) {
                    if ($deducted >= $qtyInt) {
                        break;
                    }
                    $take = min($qtyInt - $deducted, (int) $batch->remaining_quantity);
                    $batch->decrement('remaining_quantity', $take);
                    $deducted += $take;
                    if ($batchId === null) {
                        $batchId = $batch->id;
                    }
                }
                StockMovement::create([
                    'product_id' => $ing->product_id,
                    'batch_id' => $batchId,
                    'movement_type' => 'sold',
                    'quantity' => -$qtyInt,
                    'reference_id' => $order->id,
                ]);
            }
        }

        $orderType = \App\Models\OrderType::find($orderTypeId);
        if ($orderType && strtolower($orderType->type_name) === 'delivery' && !empty($v['address_id'])) {
            Delivery::create([
                'order_id' => $order->id,
                'address_id' => $v['address_id'],
                'zone_id' => $v['zone_id'] ?? null,
                'status' => 'pending',
            ]);
        }

        $ticketNumber = 'KT-' . $order->id . '-' . str_pad((string) random_int(1, 999), 3, '0');
        KitchenTicket::create([
            'order_id' => $order->id,
            'ticket_number' => $ticketNumber,
            'status' => 'pending',
        ]);

        $cart->update(['status' => 'checked_out']);

        return ApiResponse::success([
            'order' => $order->load(['items.menuItem', 'delivery', 'kitchenTicket', 'orderType']),
        ], 'Order placed', 201);
    }
}
