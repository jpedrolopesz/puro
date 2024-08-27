<?php

namespace Database\Factories;

use App\Models\{Admin, Conversation, User};
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $admin = Admin::first();

        return [
            "admin_id" => $admin->id,
            "user_id" => User::factory(),
            "labels" => json_encode($this->faker->words(3)),
            "subject" => $this->faker->sentence,
        ];
    }
}
