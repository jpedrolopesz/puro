<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Domain>
 */
class DomainFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "domain" => $this->faker->unique()->domainWord(),
            "tenant_id" => null,
        ];
    }

    /**
     * Define o tenant para o domínio.
     *
     * @param \App\Models\Tenant $tenant
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state([
            "tenant_id" => $tenant->id,
        ]);
    }
}
