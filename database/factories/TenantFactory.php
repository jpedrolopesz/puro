<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            //"email" => $this->faker->unique()->safeEmail,
            "status" => "active",
            "tenancy_db_name" => $this->faker->word,
            "tenancy_about" => $this->faker->sentence,
            "creator_id" => User::factory(), // Cria um usuário e usa seu ID
        ];
    }
}
