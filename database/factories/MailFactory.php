<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mail>
 */
class MailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sender = User::inRandomOrder()->first();
        $receiver = User::inRandomOrder()->first();

        if (!$sender || !$receiver) {
            throw new \Exception(
                "Não há usuários suficientes na base de dados."
            );
        }

        return [
            "id" => \Illuminate\Support\Str::uuid()->toString(),
            "sender_id" => $sender->id,
            "receiver_id" => $receiver->id,
            "name" => $this->faker->name,
            "email" => $this->faker->safeEmail,
            "subject" => $this->faker->sentence,
            "text" => $this->faker->paragraph(),
            "read" => $this->faker->boolean,
            "labels" => json_encode($this->faker->words(3)),
            "date" => now(),
        ];
    }
}
