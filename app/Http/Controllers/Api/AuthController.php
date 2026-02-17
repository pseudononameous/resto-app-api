<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService,
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->login($request);
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->authService->logout($request->user());
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->authService->register($request);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        return $this->authService->forgotPassword($request);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return $this->authService->resetPassword($request);
    }

    public function me(Request $request): JsonResponse
    {
        return $this->authService->me($request->user());
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        return $this->authService->changePassword($request, $request->user());
    }
}

