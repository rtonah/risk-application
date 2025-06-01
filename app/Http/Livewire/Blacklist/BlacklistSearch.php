<?php

namespace App\Http\Livewire\Blacklist;

use Livewire\Component;
use App\Models\Blacklist;
use App\Models\SearchLog;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BlacklistSearchAlert;
use Illuminate\Support\Facades\Notification;

class BlacklistSearch extends Component
{
    public string $query = '';
    public $results = [];

    public function updatedQuery()
    {
        $search = trim($this->query);

        if (strlen($search) >= 3) { // éviter les requêtes trop courtes
            $this->results = Blacklist::query()
                ->where('full_name', 'like', '%' . $search . '%')
                ->orWhere('national_id', 'like', '%' . $search . '%')
                ->orWhere('company_name', 'like', '%' . $search . '%')
                ->get();

            // Journalisation
            SearchLog::create([
                'user_id' => Auth::id(),
                'search_term' => $search,
                'matched_results' => $this->results->count(),
            ]);

            // Notification si des résultats sont trouvés
            if ($this->results->count() > 0) {
                Notification::route('mail', 'rijaniaina@me.com')
                ->notify(new BlacklistSearchAlert($search, $this->results, Auth::user()));
            }
        } else {
            $this->results = [];
        }
    }

    public function render()
    {
        return view('livewire.blacklist.blacklist-search');
    }
}
