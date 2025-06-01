<?php

namespace App\Http\Livewire\Blacklist;

use App\Models\Blacklist;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;


class BlacklistForm extends Component
{
    use WithFileUploads;

    public $full_name, $national_id, $reason, $document, $blacklist_type = 'client', $company_name, $notes;
    public $excel_file;

    protected $rules = [
        'full_name' => 'required|string',
        'national_id' => 'required|string|unique:blacklists,national_id',
        'reason' => 'required|string',
        'blacklist_type' => 'required|in:client,fournisseur,prestataire',
        'company_name' => 'nullable|string',
        'notes' => 'nullable|string',
        'document' => 'nullable|file|mimes:pdf|max:2048',
    ];

    public function submit()
    {
        $this->validate();

        $path = $this->document ? $this->document->store('blacklists', 'public') : null;

        Blacklist::create([
            'full_name' => $this->full_name,
            'national_id' => $this->national_id,
            'reason' => $this->reason,
            'blacklist_type' => $this->blacklist_type,
            'company_name' => $this->company_name,
            'notes' => $this->notes,
            'document_path' => $path,
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', 'Ajouté avec succès');
        $this->reset();
    }

    public function importExcel()
    {
        $this->validate(['excel_file' => 'required|file|mimes:xlsx,xls']);

        // Vérification des en-têtes
        $spreadsheet = IOFactory::load($this->excel_file->getRealPath());
        $headers = $spreadsheet->getActiveSheet()->rangeToArray('A1:F1')[0];

        $expectedHeaders = ['full_name', 'national_id', 'reason', 'blacklist_type', 'company_name', 'notes'];
        $missing = array_diff($expectedHeaders, $headers);

        if (!empty($missing)) {
            session()->flash('error', 'Champs manquants : ' . implode(', ', $missing));
            return;
        }

        // Importation
        Excel::import(new \App\Imports\BlacklistImport, $this->excel_file);

        session()->flash('success', 'Fichier importé avec succès.');
        $this->reset('excel_file');
    }

    public function render()
    {
        return view('livewire.blacklist.blacklist-form');
    }
}
