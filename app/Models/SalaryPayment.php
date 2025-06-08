<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'account_number',
        'amount',
        'label',
        'operation_code',
        'payment_type_id',
        'payment_date',
        'status',
    ];

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

}
