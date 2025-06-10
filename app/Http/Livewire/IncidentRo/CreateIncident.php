<?php

namespace App\Http\Livewire\IncidentRo;

use App\Models\IncidentRo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IncidentActivity; // <-- NOUVEAU

class CreateIncident extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $location;
    public $business_impact;
    public $incident_type;
    public $origin;
    public $attachment;
    public $priority = 'moyenne';
    public $branches_id;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'location' => 'nullable|string|max:255',
        'business_impact' => 'nullable|string',
        'incident_type' => 'nullable|string|max:255',
        'origin' => 'nullable|in:interne,externe',
        'priority' => 'required|in:faible,moyenne,élevée',
        'branches_id' => 'nullable|integer',
        'attachment' => 'nullable|file|max:2048', // max 2MB
    ];

    public function submit()
    {
        $this->validate();

        $path = $this->attachment
            ? $this->attachment->store('incidents', 'public')
            : null;

        $incident = IncidentRo::create([ // <-- Stocker l'incident créé
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'business_impact' => $this->business_impact,
            'incident_type' => $this->incident_type,
            'origin' => $this->origin,
            'priority' => $this->priority,
            'branches_id' => $this->branches_id,
            'attachment_path' => $path,
            'reported_at' => now(),
        ]);

        // <-- ENREGISTRER L'ACTIVITÉ DE CRÉATION
        IncidentActivity::create([
            'incident_ro_id' => $incident->id,
            'user_id' => Auth::id(),
            'type' => 'created',
            'description' => 'Incident créé par ' . Auth::user()->name . '.',
            'new_value' => [
                'title' => $this->title,
                'status' => $incident->status,
                'priority' => $incident->priority,
            ],
        ]);
        // FIN ENREGISTRER L'ACTIVITÉ

        session()->flash('success', 'Incident soumis avec succès.');
        $this->reset();
    }


    public function render()
    {
        return view('livewire.incident-ro.create-incident');
    }
}
