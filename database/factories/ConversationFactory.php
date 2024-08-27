<?php

namespace Database\Factories;

use App\Models\{Admin, Conversation, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $initiator = $this->faker->randomElement([
            Admin::factory()->create(),
            //  User::factory()->create(),
        ]);
        $recipient = $this->faker->randomElement([
            //  Admin::factory()->create(),
            User::factory()->create(),
        ]);

        return [
            "id" => Str::uuid()->toString(),
            "initiator_id" => $initiator->id,
            "initiator_type" => get_class($initiator),
            "recipient_id" => $recipient->id,
            "recipient_type" => get_class($recipient),
            "subject" => $this->faker->sentence,
            "labels" => json_encode($this->faker->words(3)),
        ];
    }
}
