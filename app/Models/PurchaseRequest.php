<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supervisor_id',
        'purchase_manager_id',
        'status',
        'title',
        'expected_delivery_date',
        'department',
        'priority',
        'notes',
        'items'
    ];

    // Demandeur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Superviseur
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // Responsable achat
    public function purchaseManager()
    {
        return $this->belongsTo(User::class, 'purchase_manager_id');
    }

    // Liste des articles
    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }
}
