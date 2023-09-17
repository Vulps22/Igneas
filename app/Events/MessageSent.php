<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;


	public $message;

	/**
	 * Create a new event instance.
	 */
	public function __construct(Message $message)
	{
		$this->message = $message;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array<int, \Illuminate\Broadcasting\Channel>
	 */
	public function broadcastOn(): array
	{
		//get the recipient id (which is the other user in the conversation)

		//if the sender id is equal to the first user in the conversation, then the recipient is the second user otherwise the recipient is the first user
		$recipientId = $this->message->conversation->users()[0]->id === $this->message->sender_id ? $this->message->conversation->users()[1]->id : $this->message->conversation->users()[0]->id;

		return [
			new PrivateChannel('conversation.user.'. $recipientId),
		];
	}

	/**
	 * Get the data to broadcast.
	 *
	 * @return array<mixed>
	 */
	public function broadcastWith(): array
	{
		return [
			'message' => [
				'id' => $this->message->id,
				'sender_id' => $this->message->sender_id,
				'text' => $this->message->text,
				'created_at' => $this->message->created_at->toIso8601String(),
			],
		];
	}
}
