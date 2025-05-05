<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $senderId,
        public int $receiverId,
        public string $content,
        public string $timestamp,
        public ?int $messageId = null
    )
    {}

    /**
     * Get the channels the event should broadcast on.
     * vai criar canal privado para conversa entre dois users
     * canal
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.chat.{$this->receiverId}"),
            new PrivateChannel("user.chat.{$this->senderId}"),
        ];
    }

    /**
     * evento
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'user.message.sent';
    }
}
