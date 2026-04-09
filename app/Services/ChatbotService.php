<?php

namespace App\Services;

use App\Models\SiteContent;
use App\ValueObjects\ChatBotResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    public function respond(string $memberMessage, string $memberName): ChatBotResult
    {
        $gymContext = $this->buildGymContext();

        try {
            $response = Http::withHeaders([
                'x-api-key' => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-sonnet-4-6',
                'max_tokens' => 300,
                'system' => "You are a helpful gym assistant for AmigosFitnessGym. {$gymContext}
                    Answer briefly and helpfully. If the member asks a complex question, needs billing help,
                    or is upset, respond with exactly the JSON: {\"action\":\"escalate\"}.
                    Otherwise respond normally in plain text.",
                'messages' => [
                    ['role' => 'user', 'content' => "Member {$memberName} says: {$memberMessage}"],
                ],
            ]);

            if ($response->failed()) {
                return ChatBotResult::escalate();
            }

            $text = $response->json('content.0.text', '');

            $decoded = json_decode($text, true);
            if (is_array($decoded) && ($decoded['action'] ?? '') === 'escalate') {
                return ChatBotResult::escalate();
            }

            return ChatBotResult::reply($text ?: 'Thank you for reaching out! How can I help?');
        } catch (\Throwable $e) {
            Log::error('ChatbotService error: '.$e->getMessage());

            return ChatBotResult::escalate();
        }
    }

    private function buildGymContext(): string
    {
        $hours = SiteContent::where('key', 'gym_hours')->value('value');

        return $hours ? "Gym hours: {$hours}." : '';
    }
}
