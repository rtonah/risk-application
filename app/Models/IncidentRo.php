<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentRo extends Model
{
    use HasFactory;

    protected $table = 'incident_ro';

   // app/Models/IncidentRo.php

    // ...
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'business_impact',
        'incident_type',
        'origin',
        'attachment_path',
        'priority',
        'status',
        'branches_id',
        'reported_at',
        'resolution_details', // <-- Ajoutez cette ligne
        'resolved_at',        // <-- Ajoutez cette ligne
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime', // <-- Ajoutez cette ligne
    ];
  
    // Relation avec l'utilisateur qui a rapporté l'incident
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec la branche/département
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branches_id'); // Spécifiez la clé étrangère si elle n'est pas conventionnelle
    }

    // Nouvelle relation pour les activités
    public function activities()
    {
        return $this->hasMany(IncidentActivity::class);
    }
}
