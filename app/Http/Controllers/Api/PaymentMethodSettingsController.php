<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethodSetting;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentMethodSettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $settings = PaymentMethodSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'cash' => true,
                'card' => true,
                'wallet' => false,
                'notes' => null,
            ],
        );

        return ApiResponse::success($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'cash' => 'sometimes|boolean',
            'card' => 'sometimes|boolean',
            'wallet' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        $settings = PaymentMethodSetting::firstOrCreate(['user_id' => $user->id]);
        $settings->fill($data);
        $settings->save();

        return ApiResponse::success($settings->fresh());
    }
}

