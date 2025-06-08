<?php

namespace App\Http\Livewire\Taratra;

use Livewire\Component;
use App\Models\Mvola;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;


class MvolaReport extends Component
{
    use WithPagination;
    // Ajoute cette propriété pour le bon fonctionnement de pagination Livewire
    protected $paginationTheme = 'bootstrap';

    public $fromDate;
    public $toDate;
    public $accountSearch = '';
    public $senderSearch = '';


    public function boot()
    {
        Paginator::useBootstrapFive();
    }
    public function render()
    {
        $query = Mvola::query()
            ->when($this->fromDate, fn($q) => $q->whereDate('Transaction_Date', '>=', $this->fromDate))
            ->when($this->toDate, fn($q) => $q->whereDate('Transaction_Date', '<=', $this->toDate))
            ->when($this->accountSearch, fn($q) => $q->where('Account', 'like', '%' . $this->accountSearch . '%'))
            ->when($this->senderSearch, fn($q) => $q->where('De', 'like', '%' . $this->senderSearch . '%'))
            ->orderByDesc('Transaction_Date');

        return view('livewire.taratra.mvola-report', [
            'transactions' => $query->paginate(10)
        ]);
    }
}
