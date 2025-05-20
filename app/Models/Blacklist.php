<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Blacklist extends Model
{
    use HasFactory;

    protected $table = 'blacklists';

    protected $fillable = [
        'full_name',
        'national_id',
        'reason',
        'status',
        'document_path',
        'created_by',
        'unblocked_by',
        'unblocked_at',
    ];

    protected $dates = ['unblocked_at'];

    // Relationship to the creator (usually a field agent or admin)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship to the user who unblocked
    public function unblockedBy()
    {
        return $this->belongsTo(User::class, 'unblocked_by');
    }

    // Accessor for full document URL
    public function getDocumentUrlAttribute()
    {
        return $this->document_path 
            ? Storage::disk('public')->url($this->document_path)
            : null;
    }
}
