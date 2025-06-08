<?php

namespace App\Http\Livewire;

use App\Models\Mvola as MvolaModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardComponent extends Component
{
    // Champs de recherche
    public $search = ''; // Recherche par numéro de compte ou par numéro de téléphone émetteur (champ "de")

    // Plage de dates pour les statistiques
    public $startDate;
    public $endDate;

    // Résumé des recherches
    public $summary = [];

    // Données pour les graphiques et indicateurs
    public $dailyStats;
    public $weeklyLabels;
    public $weeklyTotalsMvola;
    public $weeklyCountsMvola;
    public $weeklyTotalsAirtel;
    public $weeklyCountsAirtel;
    public $weeklyStats;
    public $chartTopClients;

    /**
     * Initialisation lors du montage du composant
     */
    public function mount()
    {
        // Plage par défaut : les 7 derniers jours
        $this->startDate = Carbon::today()->subDays(6)->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');

        $this->loadDashboardData();
    }

    /**
     * Recharge les données si l'utilisateur modifie une des dates
     */
    public function updatedStartDate() { $this->loadDashboardData(); }
    public function updatedEndDate() { $this->loadDashboardData(); }

    /**
     * Méthode principale de chargement des données
     */
    public function loadDashboardData()
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        // Inverser si les dates sont dans le mauvais ordre
        if ($start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

        // Préparation des collections pour les données
        $dates = collect();
        $mvolaSums = collect();
        $mvolaCounts = collect();
        $airtelSums = collect();
        $airtelCounts = collect();

        // Calculs par jour et par fournisseur
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates->push($date->format('Y-m-d'));

            $mvolaSums->push($this->getSumByDateAndProvider($date, 'mvola'));
            $mvolaCounts->push($this->getCountByDateAndProvider($date, 'mvola'));

            $airtelSums->push($this->getSumByDateAndProvider($date, 'airtel'));
            $airtelCounts->push($this->getCountByDateAndProvider($date, 'airtel'));
        }

        // Convertir en tableau avant d’affecter aux propriétés publiques
        $this->weeklyLabels = $dates->toArray();
        $this->weeklyTotalsMvola = $mvolaSums->toArray();
        $this->weeklyCountsMvola = $mvolaCounts->toArray();
        $this->weeklyTotalsAirtel = $airtelSums->toArray();
        $this->weeklyCountsAirtel = $airtelCounts->toArray();

        // Statistiques journalières (aujourd'hui)
        $today = Carbon::today();
        $this->dailyStats = collect(['mvola', 'orange', 'airtel'])->mapWithKeys(function ($provider) use ($today) {
            return [$provider => [
                'count' => $this->getCountByDateAndProvider($today, $provider),
                'sum' => $this->getSumByDateAndProvider($today, $provider),
            ]];
        })->toArray();

        // Statistiques de la semaine
        $combinedTotals = $mvolaSums->zip($airtelSums)->map(fn($pair) => $pair[0] + $pair[1]);
        $totalThisPeriod = $combinedTotals->sum();
        $days = $combinedTotals->count();

        // Variation approximative (à améliorer avec un vrai historique si nécessaire)
        $previousCombined = $combinedTotals->take($days)->sum();
        $percentChange = $previousCombined > 0
            ? number_format((($totalThisPeriod - $previousCombined) / $previousCombined) * 100, 2)
            : 0;

        $this->weeklyStats = [
            'total' => number_format($totalThisPeriod, 0, ',', ' '),
            'percent' => $percentChange,
        ];

        // Données du top 15 des clients
        $this->chartTopClients = $this->getTopClientsData();
    }

    /**
     * Somme des montants par jour et fournisseur
     */
    private function getSumByDateAndProvider($date, $provider)
    {
        return MvolaModel::whereDate('payment_date', $date)
            ->where('status', 'processed')
            ->where('provider', $provider)
            ->sum('Montant');
    }

    /**
     * Nombre de transactions par jour et fournisseur
     */
    private function getCountByDateAndProvider($date, $provider)
    {
        return MvolaModel::whereDate('payment_date', $date)
            ->where('status', 'processed')
            ->where('provider', $provider)
            ->count();
    }

    /**
     * Récupère les données du top 15 des clients (par total mvola + airtel)
     */
    private function getTopClientsData()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Fonction anonyme pour récupérer les totaux groupés par compte
        $clients = fn($provider) => MvolaModel::select('account', DB::raw("SUM(Montant) as total"))
            ->whereBetween('payment_date', [$startOfWeek, $endOfWeek])
            ->where('status', 'processed')
            ->where('provider', $provider)
            ->groupBy('account')
            ->get()
            ->keyBy('account');

        $clientsMvola = $clients('mvola');
        $clientsAirtel = $clients('airtel');

        $allAccounts = $clientsMvola->keys()->merge($clientsAirtel->keys())->unique();

        $combined = $allAccounts->map(function ($account) use ($clientsMvola, $clientsAirtel) {
            $mvola = $clientsMvola[$account]->total ?? 0;
            $airtel = $clientsAirtel[$account]->total ?? 0;

            return [
                'account' => $account,
                'mvola' => $mvola,
                'airtel' => $airtel,
                'total' => $mvola + $airtel,
            ];
        });

        $top = $combined->sortByDesc('total')->take(15)->values();

        return [
            'labels' => $top->pluck('account'),
            'series' => [
                $top->pluck('mvola')->map(fn($v) => (float) $v),
                $top->pluck('airtel')->map(fn($v) => (float) $v),
            ],
        ];
    }

    /**
     * Détection des changements de champs Livewire
     */
    public function updated($field)
    {
        // Recharge le résumé en cas de modification d'un champ (account ou from_tel)
        if (in_array($field, ['search'])) {
            $this->getSummary();
        }
    }

    /**
     * Génère un résumé filtré selon les champs account / de
     */

    public function getSummary()
    {
        $query = MvolaModel::query();

        // Appliquer les filtres de recherche si besoin
        if ($this->search && strlen($this->search) >= 7) {
            $query->where(function ($q) {
                $q->where('account', 'like', '%' . $this->search . '%')
                ->orWhere('de', 'like', '%' . $this->search . '%');
            });
        }

        // Cloner la requête pour éviter de la réexécuter avec des conditions modifiées
        $todayQuery = clone $query;

        // Transactions effectuées aujourd'hui
        $todayTotal = $todayQuery
            ->whereDate('Transaction_Date', Carbon::today())
            ->sum('Montant');

        // Résumé final
        $this->summary = [
            'today' => number_format($todayTotal, 0, ',', ' ') . ' Ar',
            'total' => number_format($query->sum('Montant'), 0, ',', ' ') . ' Ar',
            'count' => $query->count(),
        ];
    }


    /**
     * Rendu de la vue
     */
    public function render()
    {
        return view('livewire.dashboard-component', [
            'weeklyStats' => $this->weeklyStats,
            'weeklyLabels' => $this->weeklyLabels,
            'weeklyTotalsAirtel' => $this->weeklyTotalsAirtel,
            'weeklyCountsAirtel' => $this->weeklyCountsAirtel,
            'weeklyTotalsMvola' => $this->weeklyTotalsMvola,
            'weeklyCountsMvola' => $this->weeklyCountsMvola,
            'chartTopClients' => $this->chartTopClients,
        ]);
    }
}
