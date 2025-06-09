<?php

namespace App\Http\Livewire\Incidence;

use App\Models\incidence\ItRequest as IncidenceItRequest;
use Livewire\Component;
use App\Models\ItRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class ItRequestCreate extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $category;
    public $priority = 'normal';

    public $categories = ['Musoni', 'Odoo', 'Informatique', 'SIM Flotte', 'Mobile Banking'];
    public $priorities = ['normal', 'urgent', 'très urgent'];

    public $attachments = []; // Pour fichiers multiples

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|string',
        'priority' => 'required|string',
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // max 5MB par fichier
    ];

    public function submit()
    {
        $this->validate();

        // Exemple de stockage
        $storedFiles = [];
        foreach ($this->attachments as $file) {
            $storedFiles[] = $file->store('tickets/attachments', 'public');
        }

        // Sauvegarde du ticket
        $ticket = IncidenceItRequest::create([
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'priority' => $this->priority,
            'user_id' => auth()->id(),
        ]);

        // Optionnel : enregistrer les fichiers dans une table liée
        foreach ($storedFiles as $path) {
            $ticket->files()->create(['path' => $path]);
        }

        $this->reset(); // Nettoyage du formulaire
        session()->flash('message', 'Ticket créé avec succès avec les pièces jointes.');
    }
}
