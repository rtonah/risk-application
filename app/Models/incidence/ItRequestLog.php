<?php

namespace App\Models\incidence;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItRequestLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'it_request_id',
        'user_id',
        'action',
        'description',
    ];

    public function request()
    {
        return $this->belongsTo(ItRequest::class, 'it_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function files()
    {
        return $this->hasMany(TicketFile::class, 'ticket_id');
    }

}

