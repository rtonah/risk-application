<?php

namespace App\Models\incidence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketFile extends Model
{
    use HasFactory;

    // 🟢 Spécifie le bon nom de table
    protected $table = 'it_request_files';

    protected $fillable = [
        'ticket_id',
        'path',
    ];

    public function ticket()
    {
        return $this->belongsTo(ItRequest::class, 'ticket_id');
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function getFilenameAttribute()
    {
        return basename($this->path);
    }
}
