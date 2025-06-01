<?php

namespace App\Http\Livewire\Taratra;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Imports\MvolaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Mvola;

class MvolaImporter extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $file;
    public $successMessage = null;
    public $ignored = [];
    public $filterType = 'all';

    // Ajoute cette propriété pour le bon fonctionnement de pagination Livewire
    protected $paginationTheme = 'tailwind';


    protected $listeners = ['refreshImportedData' => 'loadImportedData'];

    public function updatedFilterType()
    {
        $this->resetPage(); // remet à la première page si on change le filtre
    }

   
    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        // Réinitialiser les doublons précédents
        MvolaImport::$ignoredTransactions = [];

        // Importer les données depuis le fichier CSV
        Excel::import(new MvolaImport, $this->file->getRealPath());

        // Récupérer les transactions ignorées (doublons)
        $this->ignored = MvolaImport::getIgnoredTransactions();

        // Réinitialiser le champ fichier
        $this->file = null;

        // Message de succès avec le nombre de doublons ignorés
        $this->successMessage = 'Importation terminée. Doublons ignorés : ' . count($this->ignored);

        // Fermer la modal via un événement JS
        $this->dispatchBrowserEvent('hide-import-modal');
        // Lancer un événement Livewire pour effacer le message après 5 secondes
        $this->dispatchBrowserEvent('message-clear');

    }


    public function render()
    {
        $query = Mvola::query();

        if ($this->filterType !== 'all') {
            $query->where('Type', $this->filterType);
        }

        return view('livewire.taratra.mvola-importer', [
            'importedData' => $query->latest()->paginate(10),
        ]);
    }
}
