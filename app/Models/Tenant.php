<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Laravel\Cashier\Billable;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasFactory, Billable;

    public static function getCustomColumns(): array
    {
        return ["id", "email", "data"];
    }

    protected $casts = [
        "trial_ends_at" => "datetime",
    ];

    public function domain()
    {
        return $this->hasOne(Domain::class);
    }
}
