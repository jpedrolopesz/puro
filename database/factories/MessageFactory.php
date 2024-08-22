<?php

namespace Database\Factories;

use App\Models\{Mail, Message};
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $mail = Mail::inRandomOrder()->first();

        return [
            "mail_id" => $mail ? $mail->id : null,
            "sender_id" => $mail->sender_id,
            "sender_type" => $mail->sender_type,
            "receiver_id" => $mail->receiver_id,
            "receiver_type" => $mail->receiver_type,
            "text" => $this->faker->sentence(),
            "date" => $this->faker->dateTime(),
        ];
    }
}
