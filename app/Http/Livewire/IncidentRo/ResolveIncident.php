<?php
// app/Livewire/IncidentRo/ResolveIncident.php

namespace App\Http\Livewire\IncidentRo;

use App\Models\IncidentRo;
use App\Models\IncidentActivity; // <-- NOUVEAU
use Livewire\Component;
use Illuminate\Support\Facades\Auth; // Pour Auth::id()

class ResolveIncident extends Component
{
    public $incident; // L'incident que nous allons résoudre
    public $resolutionDetails; // Pour la saisie des détails
    public $status; // Pour le changement de statut (résolu, clôturé)
    public $showModal = false; // Pour contrôler l'affichage de la modal

    protected $rules = [
        'resolutionDetails' => 'required|string|min:10',
        'status' => 'required|in:résolu,clôturé',
    ];

    // Écouteur d'événement pour ouvrir la modal
    protected $listeners = ['openResolveModal'];

    public function openResolveModal($incidentId)
    {
        $this->incident = IncidentRo::find($incidentId);

        if ($this->incident) {
            // Initialiser les propriétés avec les valeurs actuelles de l'incident
            $this->resolutionDetails = $this->incident->resolution_details;
            $this->status = $this->incident->status; // Peut-être 'résolu' par défaut si ouvert/en cours
            if ($this->incident->status === 'ouvert' || $this->incident->status === 'en cours') {
                $this->status = 'résolu'; // Suggérer "résolu" par défaut
            } else {
                $this->status = $this->incident->status;
            }
            $this->showModal = true;
            $this->showModal = true;
        }
    }

    public function resolveIncident()
    {
        $this->validate();

        if ($this->incident) {
            $oldStatus = $this->incident->status; // Récupérer l'ancien statut

            $this->incident->update([
                'resolution_details' => $this->resolutionDetails,
                'status' => $this->status,
                'resolved_at' => now(), // Mettre à jour la date de résolution
            ]);

            // <-- ENREGISTRER L'ACTIVITÉ DE CHANGEMENT DE STATUT/RÉSOLUTION
            $activityDescription = 'Statut changé de "' . ucfirst($oldStatus) . '" à "' . ucfirst($this->status) . '".';
            if ($this->status === 'résolu' || $this->status === 'clôturé') {
                $activityDescription .= ' Incident ' . ucfirst($this->status) . ' avec les détails : ' . $this->resolutionDetails;
            }

            IncidentActivity::create([
                'incident_ro_id' => $this->incident->id,
                'user_id' => Auth::id(), // L'utilisateur actuellement connecté
                'type' => 'status_updated',
                'description' => $activityDescription,
                'old_value' => ['status' => $oldStatus],
                'new_value' => ['status' => $this->status, 'resolution_details' => $this->resolutionDetails],
            ]);
            // FIN ENREGISTRER L'ACTIVITÉ

            session()->flash('message', 'Incident mis à jour avec succès.');
            $this->showModal = false; // Fermer la modal
            $this->dispatch('incidentUpdated'); // Émettre un événement pour rafraîchir la liste
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetValidation(); // Réinitialise les erreurs de validation
        $this->reset(['resolutionDetails', 'status']); // Réinitialise les champs
    }

    public function render()
    {
        return view('livewire.incident-ro.resolve-incident');
    }
}