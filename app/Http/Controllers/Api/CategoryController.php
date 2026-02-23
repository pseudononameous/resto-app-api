<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Category::orderBy('name');
        if ($request->boolean('with_product_count')) {
            $q->withCount('products');
        }
        return ApiResponse::success($q->get());
    }

    public function store(Request $request): JsonResponse
    {
        $v = $request->validate(['name' => 'required|string|max:100', 'active' => 'boolean']);
        $c = Category::create(['name' => $v['name'], 'active' => $v['active'] ?? true]);
        return ApiResponse::success($c, 'Created', 201);
    }

    public function show(Category $category): JsonResponse
    {
        return ApiResponse::success($category);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $category->update($request->validate(['name' => 'sometimes|string|max:100', 'active' => 'boolean']));
        return ApiResponse::success($category->fresh());
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return ApiResponse::success(null, 'Deleted');
    }
}
