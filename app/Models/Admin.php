<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\User as Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class Admin extends Auth implements AuthenticatableContract
{
    use CentralConnection, Notifiable, HasFactory;

    protected $guard = "admin";

    protected $fillable = ["name", "password", "email", "role"];

    protected $hidden = ["password", "remember_token"];
}
