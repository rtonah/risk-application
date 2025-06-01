<?php

namespace App\Http\Livewire\Blacklist;



use Livewire\Component;
use App\Models\Blacklist;
use Illuminate\Support\Facades\DB;

class BlacklistFuzzySearch extends Component
{
    public string $search = '';
    public $results = [];

    public function updatedSearch()
{
    $this->results = [];

    if (strlen($this->search) >= 3) {
        $search = $this->search;

        $this->results = Blacklist::whereRaw("SOUNDEX(full_name) = SOUNDEX(?)", [$search])
            ->orWhereRaw("SOUNDEX(company_name) = SOUNDEX(?)", [$search])
            ->orWhere("national_id", "like", "%{$search}%")
            ->get();
    }
}


    public function render()
    {
        return view('livewire.blacklist.blacklist-fuzzy-search');
    }
}
