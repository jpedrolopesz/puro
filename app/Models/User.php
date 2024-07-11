<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use function Illuminate\Events\queueable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class User extends Authenticatable
{
    use HasFactory, Notifiable, CentralConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ["name", "email", "tenant_id", "role"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::updated(
            queueable(function (User $customer) {
                if ($customer->hasStripeId()) {
                    $customer->syncStripeCustomerDetails();
                }
            })
        );
    }

    public function tenants()
    {
        return $this->tenants(Tenant::class, "creator_id");
    }
}
