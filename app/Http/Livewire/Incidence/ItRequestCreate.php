<?php

namespace App\Http\Livewire\Incidence;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\Incidence\ItRequest;
use App\Models\Incidence\ItRequestMessage;
use App\Models\User;

class ItRequestCreate extends Component
{
    use WithFileUploads, WithPagination;

    // Champs du formulaire
    public $title;
    public $description;
    public $category;
    public $priority = 'normal';
    public $attachments = []; // Fichiers joints multiples

    // Valeurs prédéfinies pour les listes déroulantes
    public $categories = ['Musoni', 'Odoo', 'Informatique', 'SIM Flotte', 'Mobile Banking'];
    public $priorities = ['normal', 'urgent', 'très urgent'];

    // Gestion des tickets et commentaires
    public $newStatus;
    public $assignedUserId;
    public $commentContent;
    public $showTicket = false;
    public $selectedTicket = null;

    // Validation
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|string',
        'priority' => 'required|string',
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5 Mo max par fichier
    ];

    // Événements écoutés
    protected $listeners = [
        'confirmDelete', 
        'deleteConfirmed', 
        'deleteComment'
    ];

    public function boot()
    {
        Paginator::useBootstrap(); // Pour compatibilité pagination Bootstrap
    }

    /**
     * Crée un nouveau ticket avec fichiers joints
     */
    public function submit()
    {
        $this->validate();

        // Sauvegarde des fichiers uploadés
        $storedFiles = collect($this->attachments)
            ->map(fn($file) => $file->store('tickets/attachments', 'public'));

        // Création du ticket
        $ticket = ItRequest::create([
            'title'       => $this->title,
            'description' => $this->description,
            'category'    => $this->category,
            'priority'    => $this->priority,
            'user_id'     => Auth::id(),
        ]);

        // Enregistrement des fichiers liés
        foreach ($storedFiles as $path) {
            $ticket->files()->create(['path' => $path]);
        }

        // Réinitialisation du formulaire
        $this->reset();

        // Notification via JS
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Ticket créé avec succès avec les pièces jointes.',
        ]);
    }

    /**
     * Affiche les détails d’un ticket et ses commentaires
     */
    public function show($id)
    {
        $this->selectedTicket = ItRequest::with(['user', 'assignedTo', 'comments.user'])->findOrFail($id);
        $this->newStatus = $this->selectedTicket->status;
        $this->assignedUserId = $this->selectedTicket->assigned_to;
        $this->showTicket = true;
    }

    /**
     * Retourne à la liste des tickets
     */
    public function backToList()
    {
        $this->showTicket = false;
        $this->selectedTicket = null;
    }

    /**
     * Ajoute un commentaire à un ticket
     */
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

    /**
     * Confirme la suppression d’un ticket (popup JS)
     */
    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('show-delete-confirmation', ['id' => $id]);
    }

    /**
     * Supprime un ticket après confirmation
     */
    public function deleteConfirmed($id)
    {
        $ticket = ItRequest::findOrFail($id);
        $ticket->delete();

        session()->flash('message', "Ticket #$id supprimé.");

        $this->resetPage();

        if ($this->selectedTicket && $this->selectedTicket->id === $id) {
            $this->backToList();
        }
    }

    /**
     * Confirme la suppression d’un commentaire
     */
    public function confirmDeleteComment($commentId)
    {
        $this->dispatchBrowserEvent('show-delete-confirmation-comment', ['id' => $commentId]);
    }

    /**
     * Supprime un commentaire appartenant à l’utilisateur connecté
     */
    public function deleteComment($commentId)
    {
        $comment = ItRequestMessage::find($commentId);

        if (!$comment) {
            session()->flash('error', 'Commentaire introuvable.');
            return;
        }

        if ($comment->user_id !== auth()->id()) {
            session()->flash('error', 'Vous n\'êtes pas autorisé à supprimer ce commentaire.');
            return;
        }

        $comment->delete();
        session()->flash('message', 'Commentaire supprimé.');

        // Recharger les commentaires du ticket pour mise à jour visuelle
        if ($this->selectedTicket) {
            $this->selectedTicket = $this->selectedTicket->fresh(['user', 'assignedTo', 'comments.user']);
        }
    }



    /**
     * Rendu de la vue avec pagination des tickets
     */
    public function render()
    {
        $tickets = ItRequest::with('user')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        return view('livewire.incidence.it-request-create', compact('tickets'));
    }
}
