<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

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
        return [
            "sender_id" => Auth::user(), // Seleciona um usuário existente aleatoriamente
            "receiver_id" => Auth::user(), // Seleciona um usuário existente aleatoriamente
            "name" => $this->faker->name,
            "email" => $this->faker->safeEmail,
            "subject" => $this->faker->sentence,
            "text" => $this->faker->paragraph,
            "read" => $this->faker->boolean,
            "labels" => $this->faker->words(3, true),
        ];
    }
}
