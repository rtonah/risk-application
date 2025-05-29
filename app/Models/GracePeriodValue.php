<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GracePeriodValue extends Model
{
    protected $fillable = [
        'loan_duration',
        'grace_period_capital',
        'grace_period_interest_payment',
        'grace_on_interest_charged',
    ];
}
