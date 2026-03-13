<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use OpenAI\Client;

/**
 * App Preview (Turn your website into an app): analyze content and generate
 * a mobile app mockup image via OpenAI. Same pattern as intellect-edge:
 * OPENAI_API_KEY from config, OpenAI client for chat + images.
 */
class AppPreviewService
{
    private function client(): Client
    {
        $apiKey = config('services.openai.api_key');
        if (empty($apiKey)) {
            throw new \InvalidArgumentException('OPENAI_API_KEY is not set. Add it to .env to use the App Preview feature (e.g. copy from intellect-edge project).');
        }

        return \OpenAI::client($apiKey);
    }

    /**
     * Build a DALL-E prompt from website URL, style preference, and optional file context.
     */
    private function buildImagePrompt(string $websiteUrl, string $stylePrompt, array $uploadedFiles): string
    {
        $context = "Website or business: {$websiteUrl}.";
        if (! empty($stylePrompt)) {
            $context .= " Desired style: {$stylePrompt}.";
        }
        if (count($uploadedFiles) > 0) {
            $context .= ' User also provided ' . count($uploadedFiles) . ' file(s) as reference.';
        }

        return "A single, photorealistic iPhone mobile app mockup in portrait orientation showing the home screen. "
            . "The app is for: {$context} "
            . "Include: top navigation bar with app logo area, hero banner or featured section, 2-3 content or product cards, "
            . "clear typography and modern UI elements, bottom tab bar with 4-5 icons. "
            . "Clean, premium, iOS-style design. No text in the image except subtle placeholder labels. "
            . "High fidelity UI mockup, as if screenshot from a real device.";
    }

    /**
     * Generate app preview image URL. Logs lead data for sales (same pattern as intellect-edge data handling).
     *
     * @param  array{name: string, email: string, phone?: string, website_url: string, style_prompt?: string}  $lead
     * @param  array<int, UploadedFile>  $files
     * @return array{preview_image_url?: string, preview_image_base64?: string}
     */
    public function generate(array $lead, array $files = []): array
    {
        $websiteUrl = $lead['website_url'] ?? '';
        $stylePrompt = $lead['style_prompt'] ?? '';

        $prompt = $this->buildImagePrompt($websiteUrl, $stylePrompt, $files);

        $client = $this->client();
        $response = $client->images()->create([
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'n' => 1,
            'size' => '1024x1792',
            'response_format' => 'url',
            'quality' => 'standard',
        ]);

        $url = $response->data[0]->url ?? null;
        if ($url === null) {
            throw new \RuntimeException('OpenAI did not return an image URL.');
        }

        // Log lead for sales (same data handling as spec: name, email, phone, website, files, prompt)
        Log::info('App preview lead', [
            'name' => $lead['name'] ?? '',
            'email' => $lead['email'] ?? '',
            'phone' => $lead['phone'] ?? null,
            'website_url' => $websiteUrl,
            'style_prompt' => $stylePrompt,
            'file_count' => count($files),
        ]);

        return ['preview_image_url' => $url];
    }
}
