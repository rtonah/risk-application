<?php

namespace App\Http\Livewire\Purchase;

use Livewire\Component;
use App\Models\PurchaseRequest;

class PurchaseRequestList extends Component
{
    public $purchaseRequests;

    public function mount()
    {
        // Charger toutes les demandes d'achat, tu peux filtrer selon l'utilisateur ou le rôle
        $this->purchaseRequests = PurchaseRequest::with('user')->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.purchase.purchase-request-list', [
            'purchaseRequests' => $this->purchaseRequests,
        ]);

    }
}
