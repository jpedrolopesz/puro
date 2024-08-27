<?php

namespace Database\Factories;

use App\Models\{Conversation, Message};
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $conversation = Conversation::inRandomOrder()->first();

        $sender = $this->faker->randomElement([
            $conversation->initiator,
            $conversation->recipient,
        ]);

        return [
            "conversation_id" => $conversation->id,
            "sender_id" => $sender->id,
            "sender_type" => get_class($sender),
            "content" => $this->faker->paragraph,
            "read" => $this->faker->boolean,
        ];
    }
}
