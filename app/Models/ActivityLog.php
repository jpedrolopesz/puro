<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ["tenancy_id", "activity", "date"];

    public function tenancy()
    {
        return $this->belongsTo(Tenant::class, "tenancy_id");
    }
}
