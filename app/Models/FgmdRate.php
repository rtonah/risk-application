<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FgmdRate extends Model
{
    use HasFactory;
    protected $fillable = ['min_days', 'max_days', 'rate'];

}


