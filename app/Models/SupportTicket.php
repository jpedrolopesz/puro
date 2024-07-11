<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = ["tenancy_id", "subject", "status", "created_at"];

    public function tenancy()
    {
        return $this->belongsTo(Tenant::class);
    }
}
