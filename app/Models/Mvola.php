<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mvola extends Model
{
    use HasFactory;

    protected $table = 'mvolas'; // Nom explicite au pluriel

    protected $fillable = [
        'Transaction_Date',
        'Transaction_Id',
        'Tsansaction_Initiateur',
        'Type',
        'Canal',
        'Status',
        'Compte',
        'Montant',
        'RRP',
        'De',
        'Vers',
        'Balance_avant',
        'Balance_apres',
        'Details_1',
        'Account',
        'Validateur',
        'Num_notif',
        'code_operation',
        'status',
        'payment_date',
        'processed_by',
        'is_ready',
        'processing_attempts',
        'last_error_message',
        'provider'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];
}
