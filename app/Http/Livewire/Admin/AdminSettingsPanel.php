<?php

// app/Http/Livewire/AdminSettingsPanel.php
namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\AppSetting;
use App\Models\CbsCredential;

class AdminSettingsPanel extends Component
{
    public $env_mode = 'demo'; // initialisé
    public $domain_cbs;
    public $cbs_domains = [];

    public $email_recepteur;
    public $email_achat;
    public $email_audit;
    public $types_paiement = [];

    public function mount()
    {
        $this->loadCbsDomains();
        $this->env_mode = AppSetting::where('key', 'env_mode')->value('value') ?? 'demo';
        $this->email_recepteur = AppSetting::where('key', 'email_recepteur')->value('value') ?? '';
        $this->email_audit = AppSetting::where('key', 'email_audit')->value('value') ?? '';
        $this->email_achat = AppSetting::where('key', 'email_achat')->value('value') ?? '';

        $raw = json_decode(AppSetting::where('key', 'types_paiement')->value('value') ?? '{}', true);

        $this->types_paiement = collect($raw)
        ->map(fn($id, $key) => ['key' => $key, 'id' => $id])
        ->values()
        ->toArray();

    }

    public function updatedEnvMode()
    {
        $this->domain_cbs = $this->cbs_domains[$this->env_mode] ?? '';
    }

    public function loadCbsDomains()
    {
        $records = \App\Models\CbsCredential::all()->keyBy('name');
        foreach ($records as $name => $credential) {
            $this->cbs_domains[$name] = $credential->domaine;
        }

        $this->domain_cbs = $this->cbs_domains[$this->env_mode] ?? '';
    }


    public function addType()
    {
         $this->types_paiement[] = ['key' => '', 'id' => ''];
    }

    public function removeType($index)
    {
        unset($this->types_paiement[$index]);
        $this->types_paiement = array_values($this->types_paiement);
    }

    public function save()
    {
        AppSetting::updateOrCreate(['key' => 'env_mode'], ['value' => $this->env_mode]);
        AppSetting::updateOrCreate(['key' => 'email_audit'], ['value' => $this->email_audit]);
        AppSetting::updateOrCreate(['key' => 'email_achat'], ['value' => $this->email_achat]);
        AppSetting::updateOrCreate(['key' => 'email_recepteur'], ['value' => $this->email_recepteur]);

        #.. Transforme la liste en tableau associatif clé => valeur
        $types = [];
        foreach ($this->types_paiement as $item) {
            if (!empty($item['key'])) {
                $types[$item['key']] = $item['id'];
            }
        }

        AppSetting::updateOrCreate(['key' => 'types_paiement'], ['value' => json_encode($types)]);

        // Enregistrer/mettre à jour le domaine CBS du mode actif
        CbsCredential::updateOrCreate(
            ['name' => $this->env_mode],
            ['domaine' => $this->domain_cbs]
        );

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Paramètres mis à jour.'
        ]);
        // session()->flash('message', 'Paramètres mis à jour.');
    }

    public function render()
    {
        return view('livewire.admin.admin-settings-panel');
    }
}
