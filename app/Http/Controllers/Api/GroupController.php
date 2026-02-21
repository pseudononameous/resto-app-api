<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(): JsonResponse { return ApiResponse::success(Group::orderBy('group_name')->get()); }
    public function store(Request $request): JsonResponse { $v = $request->validate(['group_name' => 'nullable|string|max:100', 'permission' => 'nullable|string']); return ApiResponse::success(Group::create($v), 'Created', 201); }
    public function show(Group $group): JsonResponse { return ApiResponse::success($group); }
    public function update(Request $request, Group $group): JsonResponse { $group->update($request->validate(['group_name' => 'nullable|string|max:100', 'permission' => 'nullable|string'])); return ApiResponse::success($group->fresh()); }
    public function destroy(Group $group): JsonResponse { $group->delete(); return ApiResponse::success(null, 'Deleted'); }
}
