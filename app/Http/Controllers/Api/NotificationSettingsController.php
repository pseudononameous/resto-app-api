<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationSettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $settings = NotificationSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'email_orders' => true,
                'email_reports' => true,
                'sms_delivery_updates' => false,
                'in_app_kitchen_alerts' => true,
            ],
        );

        return ApiResponse::success($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'email_orders' => 'sometimes|boolean',
            'email_reports' => 'sometimes|boolean',
            'sms_delivery_updates' => 'sometimes|boolean',
            'in_app_kitchen_alerts' => 'sometimes|boolean',
        ]);

        $settings = NotificationSetting::firstOrCreate(['user_id' => $user->id]);
        $settings->fill($data);
        $settings->save();

        return ApiResponse::success($settings->fresh());
    }
}

