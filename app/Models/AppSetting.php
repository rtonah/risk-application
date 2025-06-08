<?php

// app/Models/AppSetting.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];
    public $timestamps = true;
}
