<?php
// app/Models/SearchLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'search_term',
        'matched_results',
        'searched_at',
    ];

    protected $casts = [
        'searched_at' => 'datetime',
    ];
}
