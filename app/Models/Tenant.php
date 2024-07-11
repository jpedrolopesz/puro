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
        return [
            "id",
            "email",
            "data",
            "tenancy_name",
            "status",
            "payment_status",
            "subscription_level",
            "tenancy_db_name",
            "tenancy_about",
            "creator_id",
        ];
    }

    protected $casts = [
        "trial_ends_at" => "datetime",
    ];

    public function domain()
    {
        return $this->hasOne(Domain::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, "creator_id");
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function resourceUsage()
    {
        return $this->hasOne(ResourceUsage::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }
}
