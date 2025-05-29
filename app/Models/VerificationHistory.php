<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationHistory extends Model
{
    use HasFactory;
        protected $fillable = [
        'user_id',
        'loan_number',
        'loan_duration_days',
        'grace_capital_conform',
        'grace_interest_conform',
        'grace_interest_charged_conform',
        'standing_instruction_activated',
        'fgmd_conform',
        'fgmd_expected_rate',
        'fgmd_actual_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
