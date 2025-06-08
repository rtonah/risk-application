<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class CbsCredential extends Model
{
    protected $fillable = [
        'name', 'domaine', 'login', 'password', 'token',
    ];


    // Accessors et mutators pour cryptage automatique

    public function setLoginAttribute($value)
    {
        $this->attributes['login'] = Crypt::encryptString($value);
    }

    public function getLoginAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    public function getPasswordAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
