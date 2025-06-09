<?php

namespace App\Models\incidence;

use App\Models\incidence\ItRequestLog as IncidenceItRequestLog;
use App\Models\incidence\ItRequestMessage as ItRequestComment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\ItRequestMessage;
use App\Models\ItRequestLog;

class ItRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'status',
        'priority',
        'assigned_to',
        'due_at',
        'closed_at',
    ];

    protected $dates = [
        'due_at',
        'closed_at',
    ];

    // 🔁 Demandeur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔁 Agent IT assigné
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // 💬 Messages liés
    public function comments()
    {
        return $this->hasMany(ItRequestComment::class);
    }


    // 🧾 Logs / Historique
    public function logs()
    {
        return $this->hasMany(IncidenceItRequestLog::class);
    }

    // 📌 Statut booléen
    public function isClosed()
    {
        return $this->status === 'traite';
    }

    // 🕒 Ticket en retard ?
    public function isOverdue()
    {
        return $this->due_at && now()->greaterThan($this->due_at) && !$this->isClosed();
    }
    
    public function files()
    {
        return $this->hasMany(TicketFile::class, 'ticket_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }


}
