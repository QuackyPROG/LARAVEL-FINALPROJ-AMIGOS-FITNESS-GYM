<?php

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('support.admin', function (User $user): bool {
    return $user->isAdmin();
});

Broadcast::channel('support.conversation.{conversationId}', function (User $user, int $conversationId): bool {
    if ($user->isAdmin()) {
        return true;
    }

    if (! $user->isMember()) {
        return false;
    }

    return Conversation::whereKey($conversationId)
        ->where('member_id', $user->id)
        ->exists();
});
