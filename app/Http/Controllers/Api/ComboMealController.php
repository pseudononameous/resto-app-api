<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ComboMeal;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComboMealController extends Controller
{
    public function index(): JsonResponse { return ApiResponse::success(ComboMeal::orderBy('name')->get()); }
    public function store(Request $request): JsonResponse { $v = $request->validate(['name' => 'nullable|string|max:150', 'price' => 'nullable|numeric|min:0', 'active' => 'boolean']); return ApiResponse::success(ComboMeal::create(array_merge($v, ['active' => $v['active'] ?? true])), 'Created', 201); }
    public function show(ComboMeal $combo_meal): JsonResponse { return ApiResponse::success($combo_meal); }
    public function update(Request $request, ComboMeal $combo_meal): JsonResponse { $combo_meal->update($request->validate(['name' => 'nullable|string|max:150', 'price' => 'nullable|numeric|min:0', 'active' => 'boolean'])); return ApiResponse::success($combo_meal->fresh()); }
    public function destroy(ComboMeal $combo_meal): JsonResponse { $combo_meal->delete(); return ApiResponse::success(null, 'Deleted'); }
}
