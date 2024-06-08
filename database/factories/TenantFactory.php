<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
            "data" => json_encode([
                "settings" => [
                    "theme" => fake()->randomElement(["light", "dark"]),
                    "language" => fake()->randomElement(["en", "pt", "es"]),
                ],
            ]),
        ];
    }
}
