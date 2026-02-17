<?php

namespace App\Services;

use App\Contracts\Services\AuthServiceInterface;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthService implements AuthServiceInterface
{
    public function login(LoginRequest $request): JsonResponse
    {
        $email = $request->validated('email');
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            return ApiResponse::error('The credentials provided were incorrect.', Response::HTTP_UNAUTHORIZED);
        }

        $tokenName = $request->validated('client_name', 'api_token');
        $expiresAt = now()->addHours(12);
        $token = $user->createToken($tokenName, ['*'], $expiresAt)->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'token_name' => $tokenName,
            'expires_at' => $expiresAt->toIso8601String(),
            'user' => $user,
        ]);
    }

    public function logout(Authenticatable $user): JsonResponse
    {
        $user->currentAccessToken()?->delete();

        return ApiResponse::noContent();
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
        ]);

        $tokenName = $request->input('client_name', 'api_token');
        $expiresAt = now()->addHours(12);
        $token = $user->createToken($tokenName, ['*'], $expiresAt)->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'token_name' => $tokenName,
            'expires_at' => $expiresAt->toIso8601String(),
            'user' => $user,
        ], null, Response::HTTP_CREATED);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            return ApiResponse::error('Unable to send reset link.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return ApiResponse::success(null, 'If that email exists, we have sent a password reset link.', Response::HTTP_OK);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => ['This password reset token is invalid or expired.'],
            ]);
        }

        return ApiResponse::success(null, 'Password has been reset.', Response::HTTP_OK);
    }

    public function me(Authenticatable $user): JsonResponse
    {
        return ApiResponse::success($user);
    }

    public function changePassword(ChangePasswordRequest $request, Authenticatable $user): JsonResponse
    {
        $user->update(['password' => $request->validated('password')]);

        return ApiResponse::success(null, 'Password updated.', Response::HTTP_OK);
    }
}

