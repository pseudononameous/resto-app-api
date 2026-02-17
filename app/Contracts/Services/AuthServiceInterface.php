<?php

namespace App\Contracts\Services;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

interface AuthServiceInterface
{
    public function login(LoginRequest $request): JsonResponse;

    public function logout(Authenticatable $user): JsonResponse;

    public function register(RegisterRequest $request): JsonResponse;

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse;

    public function resetPassword(ResetPasswordRequest $request): JsonResponse;

    public function me(Authenticatable $user): JsonResponse;

    public function changePassword(ChangePasswordRequest $request, Authenticatable $user): JsonResponse;
}

