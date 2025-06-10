<?php

namespace App\Http\Livewire\IncidentRo;

use App\Models\IncidentRo;
use Livewire\Component;
use Illuminate\Support\Facades\Storage; // Pour le téléchargement de fichiers

class ShowIncident extends Component
{
    public $incident;
    public $showModal = false;
    public $activities = []; // Pour stocker l'historique des activités

    // Écouteur d'événement pour ouvrir la modal
    protected $listeners = ['openShowModal'];

    public function openShowModal($incidentId)
    {
        // Charger l'incident avec ses relations user, branch ET activities
        $this->incident = IncidentRo::with(['user', 'branch', 'activities' => function($query) {
            $query->orderBy('created_at', 'desc')->with('user'); // Trier et charger l'utilisateur de l'activité
        }])->find($incidentId);

        if ($this->incident) {
            $this->activities = $this->incident->activities; // Assigner les activités
            $this->showModal = true;
        }
        
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset('incident'); // Réinitialiser l'incident pour vider la modal
    }

    public function downloadAttachment()
    {
        if ($this->incident && $this->incident->attachment_path) {
            // Assurez-vous que le chemin est configuré pour le disque 'public'
            // et que le fichier existe
            if (Storage::disk('public')->exists($this->incident->attachment_path)) {
                return Storage::disk('public')->download($this->incident->attachment_path);
            } else {
                session()->flash('error', 'Le fichier joint n\'existe pas.');
            }
        } else {
            session()->flash('error', 'Aucune pièce jointe disponible.');
        }
    }

    public function render()
    {
        return view('livewire.incident-ro.show-incident');
    }
}