<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        return ApiResponse::success($roles);
    }

    public function store(Request $request): JsonResponse
    {
        $v = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:roles,slug',
            'description' => 'nullable|string|max:500',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);
        $v['slug'] = $v['slug'] ?? Str::slug($v['name']);
        $role = Role::create([
            'name' => $v['name'],
            'slug' => $v['slug'],
            'description' => $v['description'] ?? null,
        ]);
        if (! empty($v['permission_ids'] ?? [])) {
            $role->permissions()->sync($v['permission_ids']);
        }
        return ApiResponse::success($role->load('permissions'), 'Created', 201);
    }

    public function show(Role $role): JsonResponse
    {
        return ApiResponse::success($role->load('permissions'));
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $v = $request->validate([
            'name' => 'sometimes|string|max:100',
            'slug' => 'sometimes|string|max:100|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);
        if (isset($v['name'])) {
            $role->name = $v['name'];
        }
        if (isset($v['slug'])) {
            $role->slug = $v['slug'];
        }
        if (array_key_exists('description', $v)) {
            $role->description = $v['description'];
        }
        $role->save();
        if (array_key_exists('permission_ids', $v)) {
            $role->permissions()->sync($v['permission_ids'] ?? []);
        }
        return ApiResponse::success($role->fresh(['permissions']));
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return ApiResponse::success(null, 'Deleted');
    }

    public function syncPermissions(Request $request, Role $role): JsonResponse
    {
        $v = $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);
        $role->permissions()->sync($v['permission_ids']);
        return ApiResponse::success($role->fresh(['permissions']));
    }
}
