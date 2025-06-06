<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
 /*    protected $fillable = [
        'name',
        'email',
        'password',
    ]; */
    protected $guarded=[];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function supervisedRequests()
    {
        return $this->hasMany(PurchaseRequest::class, 'supervisor_id');
    }

    public function managedRequests()
    {
        return $this->hasMany(PurchaseRequest::class, 'purchase_manager_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


}
