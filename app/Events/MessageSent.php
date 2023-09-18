<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;


	public $message;
	private $recipientId;

	/**
	 * Create a new event instance.
	 */
	public function __construct(Message $message)
	{
		$this->message = $message;
		
		$users = $this->message->conversation()->users();
		$this->recipientId = 0;
		$this->recipientId = $users[0]->id === $this->message->sender_id ? $users[1]->id : $users[0]->id;
		error_log('MessageSent constructor recipientId: ' . $this->recipientId);
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array<int, \Illuminate\Broadcasting\Channel>
	 */
	public function broadcastOn(): array
	{

		//if the sender id is equal to the first user in the conversation, then the recipient is the second user otherwise the recipient is the first user
		
		error_log('MessageSent broadcastOn recipientId: ' . $this->recipientId);
		return [
			new PrivateChannel('conversation.user.'. $this->recipientId),
		];
	}

	/**
	 * Get the data to broadcast.
	 *
	 * @return array<mixed>
	 */
	public function broadcastWith(): array
	{
		error_log('MessageSent broadcastWith called');
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
