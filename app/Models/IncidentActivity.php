<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_ro_id',
        'user_id',
        'type',
        'description',
        'old_value',
        'new_value',
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
    ];

    public function incidentRo()
    {
        return $this->belongsTo(IncidentRo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}