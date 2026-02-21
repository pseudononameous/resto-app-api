<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(): JsonResponse { return ApiResponse::success(Customer::orderBy('customer_code')->get()); }
    public function store(Request $request): JsonResponse { $v = $request->validate(['customer_code' => 'required|string|max:50|unique:customers,customer_code', 'first_name' => 'nullable|string|max:100', 'last_name' => 'nullable|string|max:100', 'phone' => 'required|string|max:50|unique:customers,phone', 'email' => 'nullable|email|max:150', 'marketing_opt_in' => 'boolean']); return ApiResponse::success(Customer::create(array_merge($v, ['marketing_opt_in' => $v['marketing_opt_in'] ?? true])), 'Created', 201); }
    public function show(Customer $customer): JsonResponse { return ApiResponse::success($customer); }
    public function update(Request $request, Customer $customer): JsonResponse { $customer->update($request->validate(['customer_code' => 'sometimes|string|max:50|unique:customers,customer_code,' . $customer->id, 'first_name' => 'nullable|string|max:100', 'last_name' => 'nullable|string|max:100', 'phone' => 'sometimes|string|max:50|unique:customers,phone,' . $customer->id, 'email' => 'nullable|email|max:150', 'marketing_opt_in' => 'boolean'])); return ApiResponse::success($customer->fresh()); }
    public function destroy(Customer $customer): JsonResponse { $customer->delete(); return ApiResponse::success(null, 'Deleted'); }
}
