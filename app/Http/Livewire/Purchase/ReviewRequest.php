<?php

namespace App\Http\Livewire\Purchase;

use Livewire\Component;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\User;
use App\Notifications\PurchaseRequestApproved;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;

class ReviewRequest extends Component
{
    public PurchaseRequest $purchaseRequest;

    public $action;
    public $notes;
    public $priority;
    public array $items = [];
    public $isEditable, $isActionable;

    public function mount(PurchaseRequest $purchaseRequest)
    {
        // $this->purchaseRequest = $purchaseRequest;
        $this->purchaseRequest = $purchaseRequest->load('items');
        $this->notes = $purchaseRequest->notes;
        $this->priority = $purchaseRequest->priority;

        // Champs modifiables seulement si statut = draft
        $this->isEditable = $purchaseRequest->status === 'draft';

        // Actions possibles sauf si statut est approved
        $this->isActionable = !in_array($purchaseRequest->status, ['approved', 'refused']);



        $this->items = array_values(
            $purchaseRequest->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ];
            }
            )->toArray()
        );

    }

    public function handleAction()
    {
        $this->validate([
            'priority' => 'required|string',
            'notes' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Mise à jour des champs généraux
            $this->purchaseRequest->update([
                'notes' => $this->notes,
                'priority' => $this->priority,
            ]);

            // Mise à jour des items
            foreach ($this->items as $index => $item) {
                $itemModel = $this->purchaseRequest->items[$index];
                $itemModel->update([
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            // Gérer le changement de statut
            if (in_array($this->action, ['approve', 'pending', 'reject'])) {
                $status = match($this->action) {
                    'approve' => 'approved',
                    'pending' => 'pending',
                    'reject' => 'refused',
                };

                $this->purchaseRequest->status = $status;
                if ($status === 'approved') {
                    $this->purchaseRequest->supervisor_id = auth()->id(); // Ajoute ceci
                }
            }

            // Mettre à jour l'utilisateur ayant modifié la demande
            $this->purchaseRequest->status_updated_by = auth()->id();
            $this->purchaseRequest->save(); // une seule sauvegarde

            DB::commit();
            // Si approuvé, envoyer un mail à l’équipe achat
            if ($this->purchaseRequest->status === 'approved') {
                $purchaseTeam = User::role('admin')->get(); // Si tu utilises Spatie

                Notification::send($purchaseTeam, new PurchaseRequestApproved($this->purchaseRequest));
            }
            return redirect()->route('purchase-requests.index')->with('success', 'Action effectuée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('save', 'Erreur : ' . $e->getMessage());
        }
    }

    #.. Send to Odoo
    public function sendToOdoo()
    {
        try {
            // Exemple : Appel à ta fonction d'envoi (à adapter)
            $odooOrderId = $this->sendPurchaseRequestToOdoo($this->purchaseRequest);

            session()->flash('success', "Demande envoyée à Odoo avec succès (ID Odoo: $odooOrderId).");
        } catch (\Exception $e) {
            $this->addError('sendToOdoo', 'Erreur lors de l’envoi à Odoo : ' . $e->getMessage());
        }
    }

    // Exemple de fonction privée qui fait le travail d’envoi via HTTP à Odoo
    private function sendPurchaseRequestToOdoo($purchaseRequest)
    {
        $url = config('services.odoo.url');
        $db = config('services.odoo.db');
        $username = config('services.odoo.username');
        $password = config('services.odoo.password');

        // 1. Authentification
        $response = Http::post($url, [
            'jsonrpc' => "2.0",
            'method' => "call",
            'id' => 1,
            'params' => [
                'service' => "common",
                'method' => "login",
                'args' => [$db, $username, $password]
            ]
        ]);

        if (!$response->successful()) {
            throw new \Exception("Authentification Odoo échouée");
        }

        $uid = $response['result'];

        // 2. Création commande achat dans Odoo
        $result = Http::post($url, [
            'jsonrpc' => "2.0",
            'method' => "call",
            'id' => 2,
            'params' => [
                'service' => "object",
                'method' => "execute_kw",
                'args' => [
                    $db,
                    $uid,
                    $password,
                    'purchase.order',
                    'create',
                    [[
                        'partner_id' => 1, // Exemple : ID fournisseur à récupérer dynamiquement
                        'date_order' => now()->toDateString(),
                        'origin' => 'Demande Laravel - ' . $purchaseRequest->id,
                        // Ici, il faudra mapper correctement les lignes d'achat
                        'order_line' => [
                            [0, 0, [
                                'product_id' => 12,
                                'name' => 'Produit Laravel',
                                'product_qty' => 10,
                                'price_unit' => 500,
                                'date_planned' => now()->addDays(3)->toDateString(),
                                'product_uom' => 1
                            ]]
                        ]
                    ]]
                ]
            ]
        ]);

        if ($result->successful()) {
            return $result['result'];
        }

        throw new \Exception("Erreur lors de la création dans Odoo");
    }


    public function removeItem($index)
    {
        if (!$this->isEditable) {
            return;
        }

        $item = $this->items[$index] ?? null;

        if ($item && isset($item['id'])) {
            // Supprime de la base si l'item existe
            PurchaseRequestItem::where('id', $item['id'])->delete();
        }

        // Supprime localement
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }



    public function render()
    {
        logger('Rendering ReviewRequest');
        return view('livewire.purchase.review-request');
    }

}
