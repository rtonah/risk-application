<?php

namespace App\Http\Livewire\Incidence;

use Livewire\Component;
use App\Models\Incidence\ItRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class IncidenceDashboard extends Component
{
    public $recentTickets;
    public $ticketsByDate;
    public $ticketsByCategory;
    public $ticketStats;
    public $technicianKpiChart;
    public $ticketStatusChart;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // 🎫 Tickets récents
        $this->recentTickets = ItRequest::latest()->take(10)->get();

        // 📅 Tickets par date (7 derniers jours)
        $this->ticketsByDate = ItRequest::whereDate('created_at', '>=', now()->subDays(6))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // 📂 Tickets par catégorie
        $this->ticketsByCategory = ItRequest::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        // 📊 Statistiques simples
        $this->ticketStats = [
            [
                'label' => 'Tickets ouverts',
                'count' => ItRequest::where('status', 'open')->count(),
                'color' => 'success',
                'icon'  => 'open.png',
            ],
            [
                'label' => 'Tickets en cours',
                'count' => ItRequest::where('status', 'in_progress')->count(),
                'color' => 'success',
                'icon'  => 'progress.png',
            ],
            [
                'label' => 'Tickets clôturés',
                'count' => ItRequest::where('status', 'closed')->count(),
                'color' => 'success',
                'icon'  => 'closed.png',
            ],
        ];

        // 👷‍♂️ Performance par technicien
        $technicians = ItRequest::select('assigned_to')
            ->whereNotNull('assigned_to')
            ->distinct()
            ->pluck('assigned_to');

        $labels = [];
        $assignedCounts = [];
        $closedCounts = [];

        foreach ($technicians as $tech) {
            $user = User::find($tech);
            $labels[] = $user ? $user->matricule : "ID #$techId";
            $assignedCounts[] = ItRequest::where('assigned_to', $tech)->count();
            $closedCounts[] = ItRequest::where('assigned_to', $tech)->where('status', 'closed')->count();
        }

        $this->technicianKpiChart = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Assignés',
                    'data' => $assignedCounts,
                    'backgroundColor' => '#60A5FA'
                ],
                [
                    'label' => 'Clôturés',
                    'data' => $closedCounts,
                    'backgroundColor' => '#34D399'
                ]
            ]
        ];

        // 📈 Répartition par statut
        $this->ticketStatusChart = [
            'labels' => ['Ouvert', 'En cours', 'Clôturé'],
            'data' => [
                ItRequest::where('status', 'open')->count(),
                ItRequest::where('status', 'in_progress')->count(),
                ItRequest::where('status', 'closed')->count()
            ]
        ];
    }

    public function render()
    {
        return view('livewire.incidence.incidence-dashboard');
    }
}
