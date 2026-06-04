<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Crypt;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $receiverId;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
        $this->receiverId = $message->receiver_id;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.user.' . $this->receiverId);
    }

     public function broadcastAs()
    {
        return 'chat.message.sent';
    }
    
    public function broadcastWith()
    {
        return [
            'receiver_id' => $this->receiverId,
            'sender_id'   => $this->message->user_id,
            'sender_name' => optional($this->message->fromUser)->fullname,
            'message'     => $this->message->body,
            'contact'     => Crypt::encrypt($this->message->user_id),
        ];
    }
}
