<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LegalDraftService
{
    private const DOCUMENT_PROMPTS = [
        'terms-and-conditions' => 'Terms & Conditions',
        'membership-contract' => 'Membership Agreement / Contract (use merge tags: {{member_name}}, {{plan_name}}, {{plan_price}}, {{start_date}}, {{gym_name}})',
        'liability-waiver' => 'Liability Waiver & Assumption of Risk',
        'privacy-policy' => 'Privacy Policy compliant with the Data Privacy Act of 2012 (Republic Act No. 10173) of the Philippines',
    ];

    public function draft(string $documentType, string $gymName): string
    {
        $docLabel = self::DOCUMENT_PROMPTS[$documentType] ?? 'Legal Document';

        $prompt = <<<TEXT
        You are a legal document drafter specialising in Philippine fitness industry contracts.

        Draft a professional, legally sound <strong>{$docLabel}</strong> for a gym named "{$gymName}" located in the Philippines.

        Requirements:
        - Written in clear, plain English suitable for a gym member to read and understand
        - Compliant with applicable Philippine laws
        - Formatted as clean HTML using only: <h2>, <h3>, <p>, <ul>, <li>, <strong>, <em>, <table>, <tr>, <td>
        - Do NOT include <html>, <body>, <head>, <style>, or <script> tags
        - Do NOT include placeholder brackets like [INSERT NAME] — use the merge tags where specified
        - Cover all standard clauses for this document type in a fitness gym context
        - Approximately 400–600 words

        Return ONLY the HTML content. No preamble, no explanation, no markdown code fences.
        TEXT;

        $response = Http::timeout(60)
            ->withHeaders([
                'x-api-key' => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-sonnet-4-6',
                'max_tokens' => 2048,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Anthropic API request failed: '.$response->status());
        }

        $content = $response->json('content.0.text');

        if (! $content) {
            throw new \RuntimeException('Anthropic API returned an empty response.');
        }

        return $content;
    }
}
