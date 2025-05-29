<?php

namespace App\Http\Livewire\Purchase;

use Livewire\Component;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\Auth;

class MyPurchaseRequests extends Component
{
    public $status = '';
    public $selectedRequest; // Pour stocker la demande sélectionnée
    public $showModal = false;

    public function getRequestsProperty()
    {
        return PurchaseRequest::where('user_id', Auth::id())
            ->when($this->status, fn($query) => $query->where('status', $this->status))
            ->latest()
            ->get();
    }


    public function showDetails($id)
    {
        $this->selectedRequest = PurchaseRequest::with('items')->find($id);
        $this->showModal = true;

        // On déclenche un event JS pour ouvrir la modal
        $this->dispatchBrowserEvent('openModal');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedRequest = null;

        // On peut aussi déclencher un event pour fermer la modal JS si besoin
        $this->dispatchBrowserEvent('closeModal');
    }


    public function render()
    {
        return view('livewire.purchase.my-purchase-requests', [
            'requests' => $this->requests,
        ]);
    }
}
