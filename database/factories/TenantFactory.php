<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            "email" => $this->faker->email,
            "name" => $this->faker->name,
            "data" => null,
            "tenancy_name" => $this->faker->company,
            "status" => "active",
            "tenancy_db_name" => $this->faker->word,
            "tenancy_about" => $this->faker->sentence,
            "creator_id" => User::factory(),
        ];
    }
}
