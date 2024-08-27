<?php

namespace Database\Factories;

use App\Models\{Conversation, Message, Admin, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $admin = Admin::first();
        $users = User::first();

        $sender = $this->faker->randomElement([$admin, $users]);

        return [
            "conversation_id" => Conversation::all(),
            "sender_id" => $sender,
            "sender_type" => get_class($sender),
            "content" => $this->faker->paragraph,
            "read" => $this->faker->boolean,

            "date" => now(),
        ];
    }
}
