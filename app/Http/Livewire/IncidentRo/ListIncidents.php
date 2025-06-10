<?php

namespace App\Http\Livewire\IncidentRo;

use App\Models\IncidentRo;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Branch; // Assurez-vous d'avoir un modèle Branch si vous filtrez par branches

class ListIncidents extends Component
{
    use WithPagination;

    // Ajout de l'écouteur d'événement
    protected $listeners = ['incidentUpdated' => '$refresh']; // Cela rafraîchira tout le composant

    // Propriétés pour la recherche
    public $search = '';

    // Propriétés pour le tri
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Propriétés pour les filtres
    public $filterStatus = '';
    public $filterIncidentType = ''; // Nouveau filtre pour le type d'incident
    public $filterOrigin = ''; // Nouveau filtre pour l'origine
    public $filterPriority = ''; // Nouveau filtre pour la priorité
    public $filterBranch = ''; // Pour filtrer par branche/département
    public $filterStartDate = null; // Pour filtrer par date de début
    public $filterEndDate = null;   // Pour filtrer par date de fin

    // Liste des branches pour le filtre (si applicable)
    public $branches = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'filterStatus' => ['except' => ''],
        'filterIncidentType' => ['except' => ''],
        'filterOrigin' => ['except' => ''],
        'filterPriority' => ['except' => ''],
        'filterBranch' => ['except' => ''],
        'filterStartDate' => ['except' => null],
        'filterEndDate' => ['except' => null],
    ];

    public function mount()
    {
        // Charger les branches une seule fois lors du montage du composant
        // Assurez-vous que le modèle Branch existe et est correctement configuré
        $this->branches = Branch::orderBy('name')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterIncidentType()
    {
        $this->resetPage();
    }

    public function updatingFilterOrigin()
    {
        $this->resetPage();
    }

    public function updatingFilterPriority()
    {
        $this->resetPage();
    }

    public function updatingFilterBranch()
    {
        $this->resetPage();
    }

    public function updatingFilterStartDate()
    {
        $this->resetPage();
    }

    public function updatingFilterEndDate()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    // NOUVELLE MÉTHODE POUR OUVRIR LA MODAL DE RÉSOLUTION
    public function openResolveModalForIncident($incidentId)
    {
        // Émet un événement Livewire qui sera écouté par le composant ResolveIncident
        // La syntaxe pour émettre un événement depuis le PHP (Livewire 2) est $this->emit()
        $this->emit('openResolveModal', $incidentId);
    }

    // Nouvelle méthode pour ouvrir la modal d'affichage des détails
    public function openShowModalForIncident($incidentId)
    {
        // Émet un événement Livewire qui sera écouté par le composant ShowIncident
        // La syntaxe pour émettre un événement depuis le PHP (Livewire 2) est $this->emit()
        $this->emit('openShowModal', $incidentId);
    }



    public function render()
    {
        $query = IncidentRo::query();

        // Recherche
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%')
                  ->orWhere('incident_type', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($q2) { // Supposons que vous avez une relation user
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('branch', function ($q2) { // Supposons que vous avez une relation branch
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filtres
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterIncidentType) {
            $query->where('incident_type', $this->filterIncidentType);
        }

        if ($this->filterOrigin) {
            $query->where('origin', $this->filterOrigin);
        }

        if ($this->filterPriority) {
            $query->where('priority', $this->filterPriority);
        }

        if ($this->filterBranch) {
            $query->where('branches_id', $this->filterBranch);
        }

        if ($this->filterStartDate) {
            $query->whereDate('reported_at', '>=', $this->filterStartDate);
        }

        if ($this->filterEndDate) {
            $query->whereDate('reported_at', '<=', $this->filterEndDate);
        }

        // Tri
        $query->orderBy($this->sortField, $this->sortDirection);

        // Récupérer les incidents avec pagination
        $incidents = $query->with(['user', 'branch'])->paginate(10); // Charge les relations user et branch

        return view('livewire.incident-ro.list-incidents', [
            'incidents' => $incidents,
        ]);
    }
}