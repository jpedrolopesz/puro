<?php

namespace Database\Factories;

use App\Models\{Mail, User, Admin, Message};
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $admin = Admin::inRandomOrder()->first();
        if (!$admin) {
            $admin = Admin::factory()->create();
        }

        $sender = $this->faker->randomElement([
            User::factory()->create(),
            $admin,
        ]);

        return [
            "mail_id" => Mail::factory(),
            "sender_id" => $sender->id,
            "sender_type" => get_class($sender),
            "text" => $this->faker->paragraph(),
            "date" => $this->faker->dateTime(),
        ];
    }
}
