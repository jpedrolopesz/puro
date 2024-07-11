<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceUsage extends Model
{
    use HasFactory;

    protected $fillable = ["tenancy_id", "storage", "users"];

    public function tenancy()
    {
        return $this->belongsTo(Tenant::class);
    }
}
