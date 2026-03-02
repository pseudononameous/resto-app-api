<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaxSetting;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaxSettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $settings = TaxSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'default_rate' => 10.0,
                'inclusive_pricing' => false,
            ],
        );

        return ApiResponse::success($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'default_rate' => 'sometimes|numeric|min:0|max:100',
            'inclusive_pricing' => 'sometimes|boolean',
        ]);

        $settings = TaxSetting::firstOrCreate(['user_id' => $user->id]);
        $settings->fill($data);
        $settings->save();

        return ApiResponse::success($settings->fresh());
    }
}

