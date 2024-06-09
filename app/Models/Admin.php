<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class Admin extends Model
{
    use CentralConnection, Notifiable, HasFactory;

    protected $fillable = ["name", "email", "password"];

    protected $hidden = ["password", "remember_token"];
}
