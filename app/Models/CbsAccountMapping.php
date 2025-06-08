<?php
// app/Models/CbsAccountMapping.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbsAccountMapping extends Model
{
    protected $fillable = ['old_account', 'new_account'];
}
