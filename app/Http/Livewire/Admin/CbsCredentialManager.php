<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\CbsCredential;

class CbsCredentialManager extends Component
{
    public $form = [
        'name' => '',
        'domaine' => '',
        'login' => '',
        'password' => '',
        'token' => '',
    ];
    public $editId = null;
    protected $listeners = ['triggerDelete' => 'delete'];
    
    public function render()
    {
        return view('livewire.admin.cbs-credential-manager', [
            'credentials' => CbsCredential::all(),
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'form.name' => 'required|string',
            'form.domaine' => 'required|url',
            'form.login' => 'required|string',
            'form.password' => 'required|string',
            'form.token' => 'nullable|string',
        ]);

        // Extraction des données validées
        $data = $validated['form'];

        // Mise à jour ou création
        CbsCredential::updateOrCreate(
            ['id' => $this->editId],
            $data
        );

        $this->reset('form', 'editId');

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Configuration enregistrée avec succès.'
        ]);
    }

    public function edit($id)
    {
        $cred = CbsCredential::findOrFail($id);
        $this->form = $cred->toArray();
        $this->editId = $id;
    }

    public function delete($id)
    {
        CbsCredential::findOrFail($id)->delete();

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Enregistrement supprimé.'
        ]);
    }
}
