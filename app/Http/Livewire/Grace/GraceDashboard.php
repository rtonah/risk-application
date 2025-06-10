<?php

namespace App\Http\Livewire\Grace;

use Livewire\Component;
use App\Models\VerificationHistory;
use Livewire\WithPagination;
use Carbon\Carbon;


class GraceDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = null;

    public $weeklyLabels = [];
    public $weeklyCounts = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = VerificationHistory::query()
            ->with('user')
            ->when($this->search, fn($q) =>
                $q->where('loan_number', 'like', '%' . $this->search . '%')
            )
            ->when($this->statusFilter !== null, function ($q) {
                $q->where(function ($query) {
                    $query->where('fgmd_conform', $this->statusFilter)
                        ->orWhere('grace_capital_conform', $this->statusFilter)
                        ->orWhere('grace_interest_conform', $this->statusFilter)
                        ->orWhere('grace_interest_charged_conform', $this->statusFilter);
                });
            })
            ->latest();

        return view('livewire.grace.grace-dashboard', [
            'verifications' => $query->paginate(10),
        ]);
    }

    public function mount()
    {
        $this->loadWeeklyStats();
    }

    public function loadWeeklyStats()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $verifications = VerificationHistory::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $this->weeklyLabels = [];
        $this->weeklyCounts = [];

        foreach (range(0, 6) as $i) {
            $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $this->weeklyLabels[] = Carbon::createFromFormat('Y-m-d', $date)->format('D d');
            $this->weeklyCounts[] = $verifications[$date]->total ?? 0;
        }
    }

}
