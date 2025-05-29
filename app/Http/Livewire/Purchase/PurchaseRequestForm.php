<?php

namespace App\Http\Livewire\Purchase;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\DB;

class PurchaseRequestForm extends Component
{
    public $items = [];
    public $title = '';
    public $expected_delivery_date;
    public $department = '';
    public $priority = 'Normale';
    public $notes = '';

    public function mount()
    {
        $this->items = [
            ['product_name' => '', 'quantity' => 1, 'unit_price' => 0, 'description' => '']
        ];
    }

    public function addItem()
    {
        $this->items[] = ['product_name' => '', 'quantity' => 1, 'unit_price' => 0, 'description' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Réindexation
    }



    public function submit()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'expected_delivery_date' => 'nullable|date',
            'department' => 'nullable|string|max:255',
            'priority' => 'required|in:Haute,Normale,Basse',
            'notes' => 'nullable|string',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $purchaseRequest = PurchaseRequest::create([
                'user_id' => auth()->id(),
                'title' => $this->title,
                'expected_delivery_date' => $this->expected_delivery_date,
                'department' => $this->department,
                'priority' => $this->priority,
                'notes' => $this->notes,
                'status' => 'draft',
            ]);

            foreach ($this->items as $item) {
                $purchaseRequest->items()->create([
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'description' => $item['description'] ?? null,
                ]);
            }

            DB::commit();

            // session()->flash('success', 'Demande envoyée avec succès.');
            // return redirect()->route('dashboard');
            return redirect()->back()->with('success', 'Demande envoyée avec succès.');


        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('submit', 'Erreur : ' . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.purchase.purchase-request-form');
    }
}
