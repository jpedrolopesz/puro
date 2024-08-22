<?php

namespace Database\Factories;

use App\Models\{Admin, User};
use App\Models\Message;
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
        $userIds = User::pluck("id")->toArray();
        $adminIds = Admin::pluck("id")->toArray();
        $senderType = $this->faker->randomElement([User::class, Admin::class]);
        $receiverType =
            $senderType === User::class ? Admin::class : User::class;

        // Garante que o ID é do tipo correspondente
        $senderId = $senderType::find(
            $this->faker->randomElement($senderType::pluck("id")->toArray())
        )->id;
        $receiverId = $receiverType::find(
            $this->faker->randomElement($receiverType::pluck("id")->toArray())
        )->id;

        return [
            "id" => Str::uuid()->toString(),
            "sender_id" => $senderId,
            "sender_type" => $senderType,
            "receiver_id" => $receiverId,
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

    public function withMessages(int $count = 1): self
    {
        return $this->has(Message::factory()->count($count), "messages");
    }
}
