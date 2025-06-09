<?php

namespace App\Http\Livewire\Incidence;

use Livewire\Component;
use App\Models\Incidence\ItRequest;
use Illuminate\Support\Facades\DB;

class IncidenceDashboard extends Component
{
    public $recentTickets;
    public $ticketsByDate;
    public $ticketsByCategory;
    public $ticketStats;
    public $technicianKpiChart;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->recentTickets = ItRequest::latest()->take(10)->get();

        $this->ticketsByDate = ItRequest::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        $this->ticketsByCategory = ItRequest::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category');

        $this->ticketStats = [
            [
                'label' => 'Tickets Ouverts',
                'count' => ItRequest::where('status', 'open')->count(),
                'icon'  => 'open.png',
                'color' => 'success',
            ],
            [
                'label' => 'Tickets en Cours',
                'count' => ItRequest::where('status', 'in_progress')->count(),
                'icon'  => 'progress.png',
                'color' => 'success',
            ],
            [
                'label' => 'Tickets Traités',
                'count' => ItRequest::where('status', 'closed')->count(),
                'icon'  => 'done.png',
                'color' => 'success',
            ],
        ];

        $kpiRaw = ItRequest::with('technician')
            ->select('assigned_to', DB::raw('COUNT(*) as total'))
            ->where('status', 'closed')
            ->groupBy('assigned_to')
            ->get();

        $labels = [];
        $data = [];

        foreach ($kpiRaw as $item) {
            $name = $item->technician->last_name ?? 'Non assigné';
            $labels[] = $name;
            $data[] = $item->total;
        }

        $this->technicianKpiChart = [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function render()
    {
        return view('livewire.incidence.incidence-dashboard', [
            'recentTickets' => $this->recentTickets,
            'ticketsByDate' => $this->ticketsByDate,
            'ticketsByCategory' => $this->ticketsByCategory,
            'ticketStats' => $this->ticketStats,
            'technicianKpiChart' => $this->technicianKpiChart,
        ]);
    }
}
