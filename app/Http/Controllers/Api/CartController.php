<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\MenuItem;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Cart::with(['user', 'customer', 'store', 'items.menuItem'])->orderBy('id', 'desc');
        if ($request->filled('store_id')) {
            $q->where('store_id', $request->get('store_id'));
        }
        if ($request->filled('status')) {
            $q->where('status', $request->get('status'));
        }
        if ($request->filled('user_id')) {
            $q->where('user_id', $request->get('user_id'));
        }
        if ($request->filled('customer_id')) {
            $q->where('customer_id', $request->get('customer_id'));
        }
        return ApiResponse::success($q->get());
    }

    public function store(Request $request): JsonResponse
    {
        $v = $request->validate([
            'cart_code' => 'required|string|max:50|unique:carts,cart_code',
            'user_id' => 'nullable|exists:users,id',
            'customer_id' => 'nullable|exists:customers,id',
            'table_number' => 'nullable|string|max:20',
            'status' => 'in:active,checked_out,cancelled',
            'total' => 'numeric|min:0',
            'store_id' => 'nullable|exists:stores,id',
        ]);
        $cart = Cart::create(array_merge($v, [
            'status' => $v['status'] ?? 'active',
            'total' => $v['total'] ?? 0,
            'store_id' => $v['store_id'] ?? null,
        ]));
        return ApiResponse::success($cart->load(['store']), 'Created', 201);
    }

    public function show(Cart $cart): JsonResponse
    {
        return ApiResponse::success($cart->load(['user', 'customer', 'store', 'items.menuItem']));
    }

    public function update(Request $request, Cart $cart): JsonResponse
    {
        $cart->update($request->validate([
            'cart_code' => 'sometimes|string|max:50|unique:carts,cart_code,' . $cart->id,
            'user_id' => 'nullable|exists:users,id',
            'customer_id' => 'nullable|exists:customers,id',
            'table_number' => 'nullable|string|max:20',
            'status' => 'in:active,checked_out,cancelled',
            'total' => 'numeric|min:0',
            'store_id' => 'nullable|exists:stores,id',
        ]));
        return ApiResponse::success($cart->fresh()->load(['store', 'items.menuItem']));
    }

    public function destroy(Cart $cart): JsonResponse
    {
        $cart->delete();
        return ApiResponse::success(null, 'Deleted');
    }

    public function addItem(Request $request, Cart $cart): JsonResponse
    {
        if ($cart->status !== 'active') {
            return ApiResponse::error('Cart is not active', 422);
        }
        $v = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $menuItem = MenuItem::findOrFail($v['menu_item_id']);
        $price = (float) ($menuItem->base_price ?? 0);
        $totalPrice = $price * (int) $v['quantity'];

        $existing = CartItem::where('cart_id', $cart->id)->where('menu_item_id', $v['menu_item_id'])->first();
        if ($existing) {
            $existing->quantity += (int) $v['quantity'];
            $existing->total_price = ((float) $existing->total_price) + $totalPrice;
            $existing->save();
            $item = $existing;
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'menu_item_id' => $v['menu_item_id'],
                'product_id' => null,
                'quantity' => (int) $v['quantity'],
                'total_price' => $totalPrice,
            ]);
        }
        $this->recalculateCartTotal($cart);
        return ApiResponse::success($cart->fresh()->load(['items.menuItem']), 'Added', 201);
    }

    public function removeItem(Cart $cart, CartItem $cart_item): JsonResponse
    {
        if ($cart_item->cart_id != $cart->id) {
            return ApiResponse::error('Item not in cart', 404);
        }
        $cart_item->delete();
        $this->recalculateCartTotal($cart);
        return ApiResponse::success($cart->fresh()->load(['items.menuItem']));
    }

    private function recalculateCartTotal(Cart $cart): void
    {
        $total = CartItem::where('cart_id', $cart->id)->sum('total_price');
        $cart->update(['total' => $total]);
    }
}
