<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourierSetting;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourierSettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $settings = CourierSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'own_riders' => true,
                'third_party_couriers' => false,
                'notes' => null,
            ],
        );

        return ApiResponse::success($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'own_riders' => 'sometimes|boolean',
            'third_party_couriers' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        $settings = CourierSetting::firstOrCreate(['user_id' => $user->id]);
        $settings->fill($data);
        $settings->save();

        return ApiResponse::success($settings->fresh());
    }
}

