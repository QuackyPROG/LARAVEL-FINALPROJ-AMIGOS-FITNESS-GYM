<?php

namespace App\ValueObjects;

class ChatBotResult
{
    private function __construct(
        public readonly bool $shouldEscalate,
        public readonly string $reply,
    ) {}

    public static function reply(string $text): self
    {
        return new self(false, $text);
    }

    public static function escalate(): self
    {
        return new self(true, "I'm connecting you with a staff member. Please hold on.");
    }
}
