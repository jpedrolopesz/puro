<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Message;
use App\Models\Mail;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id" => (string) Str::uuid(),
            "mail_id" => Mail::factory(),
            "sender_id" => User::factory(),
            "text" => $this->faker->paragraph(),
            "date" => $this->faker->dateTime(),
        ];
    }
}
