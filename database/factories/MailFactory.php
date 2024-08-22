<?php

namespace Database\Factories;

use App\Models\{Admin, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $senderType = $this->faker->randomElement([User::class, Admin::class]);
        $receiverType =
            $senderType === User::class ? Admin::class : User::class;

        return [
            "id" => Str::uuid()->toString(),
            "sender_id" => $senderType::factory(),
            "sender_type" => $senderType,
            "receiver_id" => $receiverType::factory(),
            "receiver_type" => $receiverType,
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
