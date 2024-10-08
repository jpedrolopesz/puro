<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use function Illuminate\Events\queueable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class User extends Authenticatable
{
    use HasFactory, Notifiable, CentralConnection, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "email",
        "role",
        "password",
        "stripe_id",
        "pm_type",
        "pm_last_four",
        "trial_ends_at",
        "tenant_id",
        "cus_id",
    ];

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

        static::creating(function ($model) {
            $model->identifier =
                "USR-" . str_pad(self::max("id") + 1, 5, "0", STR_PAD_LEFT);
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, "tenant_id");
    }

    public function initiatedConversations()
    {
        return $this->morphMany(Conversation::class, "initiator");
    }

    public function receivedConversations()
    {
        return $this->morphMany(Conversation::class, "recipient");
    }

    public function messages()
    {
        return $this->morphMany(Message::class, "sender");
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
