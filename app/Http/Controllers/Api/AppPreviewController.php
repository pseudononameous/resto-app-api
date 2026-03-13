<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AppPreviewService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * App Preview (Turn your website into an app). Public endpoint, no auth.
 * Collects lead info + content source, calls OpenAI (same pattern as intellect-edge),
 * returns generated preview image URL.
 */
class AppPreviewController extends Controller
{
    public function __construct(
        private AppPreviewService $appPreviewService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'website_url' => 'nullable|string|max:2048',
            'style_prompt' => 'nullable|string|max:1000',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpeg,jpg,png,gif,webp,pdf,csv,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error(
                $validator->errors()->first(),
                422,
                $validator->errors()
            );
        }

        $data = $validator->validated();
        $websiteUrl = $data['website_url'] ?? '';
        $files = $request->file('files') ?? [];

        if (empty($websiteUrl) && empty($files)) {
            return ApiResponse::error('Please provide a website URL or upload files.', 422);
        }

        // If only files provided, use a placeholder URL for prompt (e.g. "uploaded content")
        $websiteUrl = $websiteUrl ?: 'Business/content from uploaded files';

        $lead = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'website_url' => $websiteUrl,
            'style_prompt' => $data['style_prompt'] ?? '',
        ];

        try {
            $result = $this->appPreviewService->generate($lead, $files);

            return ApiResponse::success([
                'preview_image_url' => $result['preview_image_url'] ?? null,
                'preview_image_base64' => $result['preview_image_base64'] ?? null,
            ], 'Preview generated');
        } catch (\InvalidArgumentException $e) {
            return ApiResponse::error(
                $e->getMessage() ?: 'OpenAI is not configured. Set OPENAI_API_KEY in .env (e.g. copy from intellect-edge project).',
                503
            );
        } catch (\Throwable $e) {
            report($e);

            return ApiResponse::error(
                'Failed to generate preview. Please try again. Ensure OPENAI_API_KEY is set in .env.',
                500
            );
        }
    }
}
