<?php

namespace App\Http\Livewire\Incidence;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\incidence\ItRequest;
use App\Models\User;
use App\Models\incidence\ItRequestComment;

class ItRequestList extends Component
{
    use WithPagination;

    public $showTicket = false;
    public $selectedTicket = null;

    // Filtres
    public $search = '';
    public $filterStatus = null;
    public $filterCategory = '';
    public $filterPriority = '';

    public $statuses = ['open', 'in_progress', 'closed'];
    public $categories = ['Musoni', 'Odoo', 'Informatique', 'SIM Flotte', 'Mobile Banking'];
    public $priorities = ['normal', 'urgent', 'très urgent'];

    public $allUsers = []; // ✅ à ajouter

    protected $updatesQueryString = ['search', 'filterStatus', 'filterCategory', 'filterPriority'];
    protected $paginationTheme = 'bootstrap';

    // Formulaire de mise à jour
    public $newStatus;
    public $assignedUserId;
    public $commentContent;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingFilterCategory() { $this->resetPage(); }
    public function updatingFilterPriority() { $this->resetPage(); }

    public function mount()
    {
        $this->resetForm();
        $this->allUsers = User::orderBy('first_name')->get();
    }

    public function resetForm()
    {
        $this->newStatus = null;
        $this->assignedUserId = null;
        $this->commentContent = '';
    }

    public function show($id)
    {
        $this->selectedTicket = ItRequest::with(['user', 'assignedTo', 'comments.user'])->findOrFail($id);
        $this->newStatus = $this->selectedTicket->status;
        $this->assignedUserId = $this->selectedTicket->assigned_to;
        $this->commentContent = '';
        $this->showTicket = true;
    }

    public function backToList()
    {
        $this->showTicket = false;
        $this->selectedTicket = null;
        $this->resetForm();
    }

    public function delete($id)
    {
        $ticket = ItRequest::findOrFail($id);
        $ticket->delete();
        session()->flash('message', "Ticket #$id supprimé.");
        $this->resetPage();

        if ($this->showTicket && $this->selectedTicket && $this->selectedTicket->id == $id) {
            $this->backToList();
        }
    }

    public function updateTicket()
    {
        $this->validate([
            'newStatus' => 'required|in:open,in_progress,closed', // ✅ corrigé
            'assignedUserId' => 'nullable|exists:users,id',
        ]);

        if (!$this->selectedTicket) {
            session()->flash('error', 'Aucun ticket sélectionné.');
            return;
        }

        $this->selectedTicket->update([
            'status' => $this->newStatus,
            'assigned_to' => $this->assignedUserId,
        ]);

        session()->flash('message', 'Ticket mis à jour avec succès.');
        $this->selectedTicket = $this->selectedTicket->fresh(['user', 'assignedTo', 'comments.user']);
    }

    public function addComment()
    {
        $this->validate([
            'commentContent' => 'required|string|max:1000',
        ]);

        if (!$this->selectedTicket) {
            session()->flash('error', 'Aucun ticket sélectionné.');
            return;
        }

        $this->selectedTicket->comments()->create([
            'user_id' => auth()->id(),
            'message' => $this->commentContent,
        ]);

        $this->commentContent = '';
        $this->selectedTicket = $this->selectedTicket->fresh(['user', 'assignedTo', 'comments.user']);
    }

    public function render()
    {
        $query = ItRequest::query()->with(['user', 'assignedTo']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                  ->orWhereHas('user', function($q2) {
                      $q2->where('first_name', 'like', '%'.$this->search.'%')
                         ->orWhere('last_name', 'like', '%'.$this->search.'%');
                  });
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        if ($this->filterPriority) {
            $query->where('priority', $this->filterPriority);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.incidence.it-request-list', [
            'tickets' => $tickets,
        ]);
    }
}
