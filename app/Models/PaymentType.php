<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class);
    }
}
