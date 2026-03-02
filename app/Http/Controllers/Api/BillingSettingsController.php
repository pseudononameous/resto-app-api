<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BillingSetting;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BillingSettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $settings = BillingSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'plan' => 'Starter',
                'next_invoice_date' => null,
            ],
        );

        return ApiResponse::success($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'plan' => 'sometimes|string|max:255',
            'next_invoice_date' => 'nullable|date',
        ]);

        $settings = BillingSetting::firstOrCreate(['user_id' => $user->id]);
        $settings->fill($data);
        $settings->save();

        return ApiResponse::success($settings->fresh());
    }
}

