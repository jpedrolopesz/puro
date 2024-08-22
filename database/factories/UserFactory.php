<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tenantId = Tenant::inRandomOrder()->first()?->id;

        return [
            "identifier" => $this->generateIdentifier(),
            "name" => $this->faker->name(),
            "email" => $this->faker->unique()->safeEmail(),
            "email_verified_at" => now(),
            "password" => (static::$password ??= Hash::make("password")),
            "role" => UserRole::User,
            "tenant_id" => $tenantId,
            "remember_token" => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(
            fn(array $attributes) => [
                "email_verified_at" => null,
            ]
        );
    }

    protected function generateIdentifier()
    {
        $lastUserId = User::max("id") ?? 0;
        return "USR-" . str_pad($lastUserId + 1, 5, "0", STR_PAD_LEFT);
    }

    /**
     * Configure the factory to accept a specific tenant_id.
     */
    public function withTenant(Tenant $tenant): static
    {
        return $this->state([
            "tenant_id" => $tenant->id,
        ]);
    }
}
