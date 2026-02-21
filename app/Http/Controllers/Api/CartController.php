<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse {
        $q = Cart::with(['user', 'customer', 'store'])->orderBy('id', 'desc');
        if ($request->filled('store_id')) { $q->where('store_id', $request->get('store_id')); }
        return ApiResponse::success($q->get());
    }
    public function store(Request $request): JsonResponse {
        $v = $request->validate(['cart_code' => 'required|string|max:50|unique:carts,cart_code', 'user_id' => 'nullable|exists:users,id', 'customer_id' => 'nullable|exists:customers,id', 'table_number' => 'nullable|string|max:20', 'status' => 'in:active,checked_out,cancelled', 'total' => 'numeric|min:0', 'store_id' => 'nullable|exists:stores,id']);
        return ApiResponse::success(Cart::create(array_merge($v, ['status' => $v['status'] ?? 'active', 'total' => $v['total'] ?? 0, 'store_id' => $v['store_id'] ?? null])), 'Created', 201);
    }
    public function show(Cart $cart): JsonResponse { return ApiResponse::success($cart->load(['user', 'customer'])); }
    public function update(Request $request, Cart $cart): JsonResponse { $cart->update($request->validate(['cart_code' => 'sometimes|string|max:50|unique:carts,cart_code,' . $cart->id, 'user_id' => 'nullable|exists:users,id', 'customer_id' => 'nullable|exists:customers,id', 'table_number' => 'nullable|string|max:20', 'status' => 'in:active,checked_out,cancelled', 'total' => 'numeric|min:0', 'store_id' => 'nullable|exists:stores,id'])); return ApiResponse::success($cart->fresh()->load('store')); }
    public function destroy(Cart $cart): JsonResponse { $cart->delete(); return ApiResponse::success(null, 'Deleted'); }
}
