<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatTeamMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $teamId,
        public int $senderId,
        public string $senderName,
        public string $content,
        public string $timestamp,
        public ?int $messageId = null
    )
    {
    }

    /**
     * canal
     * @return PresenceChannel[]
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel("team.chat.$this->teamId"),
        ];
    }

    /**
     * evento
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'team.message.sent';
    }
}
