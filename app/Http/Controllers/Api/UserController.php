<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('roles')->orderBy('name')->get()->map(fn (User $u) => $this->userResource($u));
        return ApiResponse::success($users);
    }

    public function store(Request $request): JsonResponse
    {
        $v = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'integer|exists:roles,id',
        ]);
        $user = User::create([
            'name' => $v['name'],
            'email' => $v['email'],
            'password' => Hash::make($v['password']),
        ]);
        if (! empty($v['role_ids'] ?? [])) {
            $user->roles()->sync($v['role_ids']);
        }
        return ApiResponse::success($this->userResource($user->load('roles')), 'Created', 201);
    }

    public function show(User $user): JsonResponse
    {
        return ApiResponse::success($this->userResource($user->load('roles')));
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $v = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'integer|exists:roles,id',
        ]);
        if (isset($v['name'])) {
            $user->name = $v['name'];
        }
        if (isset($v['email'])) {
            $user->email = $v['email'];
        }
        if (! empty($v['password'] ?? null)) {
            $user->password = Hash::make($v['password']);
        }
        $user->save();
        if (array_key_exists('role_ids', $v)) {
            $user->roles()->sync($v['role_ids'] ?? []);
        }
        return ApiResponse::success($this->userResource($user->fresh(['roles'])));
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return ApiResponse::success(null, 'Deleted');
    }

    private function userResource(User $user): array
    {
        $user->setRelation('roles', $user->roles ?? $user->roles()->get());
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at?->toIso8601String(),
            'created_at' => $user->created_at?->toIso8601String(),
            'updated_at' => $user->updated_at?->toIso8601String(),
            'roles' => $user->roles->map(fn ($r) => ['id' => $r->id, 'name' => $r->name, 'slug' => $r->slug]),
        ];
    }
}
