<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IntegrationsSetting;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IntegrationsSettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $settings = IntegrationsSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'zapier' => false,
                'webhooks' => false,
                'pos_webhook_url' => null,
            ],
        );

        return ApiResponse::success($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'zapier' => 'sometimes|boolean',
            'webhooks' => 'sometimes|boolean',
            'pos_webhook_url' => 'nullable|string|max:255',
        ]);

        $settings = IntegrationsSetting::firstOrCreate(['user_id' => $user->id]);
        $settings->fill($data);
        $settings->save();

        return ApiResponse::success($settings->fresh());
    }
}

