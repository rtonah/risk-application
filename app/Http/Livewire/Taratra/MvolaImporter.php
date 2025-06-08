<?php

namespace App\Http\Livewire\Taratra;

use App\Imports\AirtelImport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Imports\MvolaImport;
use App\Models\CbsAccountMapping;
use App\Models\CbsCredential;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Mvola;

use Illuminate\Support\Facades\Http;
use App\Notifications\MvolaSummaryNotification;
use Illuminate\Support\Facades\Notification;


class MvolaImporter extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $file;
    public $successMessage = null;
    public $ignored = [];
    public $filterStatus = 'all';
    public $filterProvider = 'all';

    public $showModal = false;
    public $login, $password;

    public $types_paiement;
    public $payementID_mvola;
    public $payementID_airtel;


    public $token;
    public $domaine;

    #.. Variable pour la modification inline
    public $editingId = null;
    public $editCompte;

    #.. Environnement de travail
    public $env_mode = 'demo';

    // Ajoute cette propriété pour le bon fonctionnement de pagination Livewire
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshImportedData' => 'loadImportedData'];

    // ✅ Distinguer les imports mVola / Airtel
    public $fileMvola;
    public $fileAirtel;


    public function updatedFilterType()
    {
        $this->resetPage(); // remet à la première page si on change le filtre
    }

    #.. Mvola import
    public function import()
    {
        $this->validate([
            'fileMvola' => 'required|file|mimes:csv,txt',
        ]);

        // Réinitialiser les doublons précédents
        MvolaImport::$ignoredTransactions = [];

        // Importer les données depuis le fichier CSV
        Excel::import(new MvolaImport, $this->fileMvola->getRealPath());

        // Récupérer les transactions ignorées (doublons)
        $this->ignored = MvolaImport::getIgnoredTransactions();

        // Réinitialiser le champ fichier
        $this->fileMvola = null;

        // Message de succès avec le nombre de doublons ignorés
        $this->successMessage = 'Importation Mvola terminée. Doublons ignorés : ' . count($this->ignored);

        // Fermer la modal via un événement JS
        $this->dispatchBrowserEvent('hide-import-modal');
        // Lancer un événement Livewire pour effacer le message après 5 secondes
        $this->dispatchBrowserEvent('message-clear');

    }

    #.. Airtel Import
    public function importAirtel()
    {
        $this->validate([
            'fileAirtel' => 'required|file|mimes:csv,txt',
        ]);

        // Réinitialiser les doublons précédents
        AirtelImport::$ignoredTransactions = [];

        // Réinitialiser les doublons ou autres si besoin

        try {
            Excel::import(new AirtelImport, $this->fileAirtel->getRealPath());

            // Récupérer les transactions ignorées (doublons)
            $this->ignored = MvolaImport::getIgnoredTransactions();

            // Message de succès avec le nombre de doublons ignorés
            $this->successMessage = 'Importation Airtel terminée. Doublons ignorés : ' . count($this->ignored);


            // Fermer la modal si besoin
            $this->dispatchBrowserEvent('hide-import-modal');

        } catch (\Exception $e) {
            $this->successMessage = 'Erreur lors de l’import Airtel : ' . $e->getMessage();
        }

        // Réinitialiser le champ fichier
        $this->fileAirtel = null;
    }


    public function mount()
    {
        // 1. Charger tous les types de paiement
        $this->types_paiement = get_setting('types_paiement') ?? [];

        // 2. Extraire l'identifiant spécifique mvola_paiement et airtel_paiement
        $this->payementID_mvola = $this->types_paiement['mvola_paiement'] ?? null;
        $this->payementID_airtel = $this->types_paiement['airtel_paiement'] ?? null;


        // 3. Récupérer les credentials CBS du mode actif (démo ou production)
        $this->env_mode = get_setting('env_mode') ?? 'demo';
        $creds = \App\Models\CbsCredential::where('name', $this->env_mode)->first();

        if ($creds) {
            $this->token = $creds->token;
            $this->domaine = $creds->domaine;
        } else {
            // Optionnel : valeurs par défaut si aucune config trouvée
            $this->token = null;
            $this->domaine = null;
        }

        // dd([
        //     'mvola_id' => $this->mvola_id,
        //     'env' => $this->env_mode,
        //     'creds' => $creds,
        //     'token' => $this->token,
        //     'domaine' => $this->domaine
        // ]);
    }


    #.. Tester la connexion CBS
    public function testConnexionCBS()
    {
        // dd($this->token, $this->login, $this->password);
        try {
            $response = Http::withHeaders([
                'X-Fineract-Platform-TenantId' => 'acepmg',
                'x-api-key' => $this->token,
            ])
            ->timeout(20)
            ->post($this->domaine . 'authentication', [
                'username' => $this->login,
                'password' => $this->password
            ]);
            
            // dd($response->successful());

            if ($response->successful()) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }


    #.. Call API MUsoni pour transaction mVolas
    public function processTransactions()
    {
        $this->dispatchBrowserEvent('start-processing'); // ⛔ Mauvais si tu veux interagir avec Alpine

        // ✅ Correct : pour Alpine via Livewire.on()
        $this->emit('start-processing');

        $this->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tester la connexion CBS
        if (!$this->testConnexionCBS()) {
            $this->successMessage = 'Échec d’authentification : identifiants CBS invalides.';
            $this->showModal = false;
            $this->emit('stop-processing');
            return;
        }

        // Si la connexion est OK → on poursuit la transaction
        try {
            // 💡 Ta logique métier ici
            sleep(3); // Simulation de traitement


            $todayDate = now()->format('d F Y');

            // Condition pour les transaction à envoyer vers Musoni
            $momo_transaction = Mvola::where('is_ready', true)
            ->where('code_operation', 'new')
            ->whereNotNull('Account')
            ->get();

            $duplicateCount = 0;
            $successCount = 0;
            $failCount = 0;

            foreach ($momo_transaction as $data) {

                // 🔁 Mise à jour du compte s’il est mappé
                $mapping = CbsAccountMapping::where('old_account', $data->Account)->first();
                if ($mapping) {
                    $data->Account = $mapping->new_account;
                }

                try {

                    // ❗ Get ID Payement selon providers
                    if($data->provider === "mvola"){
                        $payementId = $this->payementID_mvola;
                    }
                    if($data->provider === "airtel"){
                        $payementId = $this->payementID_airtel;
                    }

                    // 📡 Appel API Musoni
                    $response = Http::withBasicAuth($this->login, $this->password)
                        ->withHeaders([
                            'X-Fineract-Platform-TenantId' => 'acepmg',
                            'x-api-key' =>  $this->token,
                        ])
                        ->timeout(60)
                        ->post($this->domaine . 'savingsaccounts/' . $data->Account . '/transactions?command=deposit', [
                            'locale' => 'en',
                            'dateFormat' => 'dd MMMM yyyy',
                            'transactionDate' => $todayDate,
                            'transactionAmount' => $data->Montant,
                            'paymentTypeId' => $payementId,
                            'accountNumber' => $data->Account,
                            'receiptNumber' => $data->Transaction_Id,
                            'bankNumber' => $data->De,
                        ]);

                    $result = json_decode($response->body());

                    if ($response->successful()) {
                        $data->code_operation = $result->resourceId ?? 'no_resource_id';
                        $data->status = 'processed';
                        $data->canal = $payementId;
                        $data->payment_date = now();
                        $successCount++;
                    } else {
                        $data->code_operation = $result->errors[0]->developerMessage ?? 'Erreur inconnue';
                        $data->status = 'failed';
                        $data->last_error_message = $data->code_operation;
                        $data->payment_date = null;
                        $failCount++;
                    }

                } catch (\Exception $e) {
                    $data->code_operation = 'Exception : ' . $e->getMessage();
                    $data->status = 'failed';
                    $data->last_error_message = $e->getMessage();
                    $failCount++;
                }

                $data->processed_by = $this->login;
                $data->processing_attempts += 1;
                $data->save();
            }


            // Fin du traitement
            $this->emit('stop-processing');

            $this->successMessage = "Transactions : $successCount réussies, $failCount échouées, $duplicateCount doublons.";

            // Envoyer l'email directement à une adresse
            Notification::route('mail', 'rijaniaina@me.com')
                ->notify(new MvolaSummaryNotification($successCount, $failCount, $duplicateCount));




        } catch (\Exception $e) {
            $this->emit('stop-processing');
            $this->successMessage = 'Erreur système : ' . $e->getMessage();
        }

        // Réinitialiser les champs après traitement
        $this->reset(['login', 'password', 'showModal']);
    }

    public function startEdit($id)
    {
        $record = Mvola::findOrFail($id);
        $this->editingId = $id;
        $this->editCompte = $record->Account;
    }

    public function saveEdit()
    {
        $record = Mvola::findOrFail($this->editingId);
        $record->Account = $this->editCompte;
        $record->status = "modified";

        $record->save();

        $this->editingId = null;
        $this->successMessage =  'Modifications enregistrées !';
    }



    public function render()
    {
        $query = Mvola::query();

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterProvider !== 'all') {
            $query->where('provider', $this->filterProvider);
        }

        $importedData = $query->latest()->paginate(10);

        $importedData->getCollection()->transform(function ($item) {
            $item->Transaction_Date = \Carbon\Carbon::parse($item->Transaction_Date)->format('d/m/Y H:i');
            return $item;
        });

        return view('livewire.taratra.mvola-importer', [
            'importedData' => $importedData,
        ]);
    }


}
