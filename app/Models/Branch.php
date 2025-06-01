<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'region',
    ];

    /**
     * Branche parente (DRA ou Agence)
     */
    public function parent()
    {
        return $this->belongsTo(Branch::class, 'parent_id');
    }

    /**
     * Enfants de cette branche (Agences ou Sous-agences)
     */
    public function children()
    {
        return $this->hasMany(Branch::class, 'parent_id');
    }

    /**
     * Utilisateurs rattachés à cette branche
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
