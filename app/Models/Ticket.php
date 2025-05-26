<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject', 'description', 'is_anonymous', 'created_by',
        'status', 'escalated_to_dg', 'closed_by', 'closed_at', 'resolution', 'assigned_to',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function knowledgeBaseArticle()
    {
        return $this->hasOne(KnowledgeBaseArticle::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    #.. Assing the ticket to another compliance team
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

}

