<?php

namespace App\Http\Livewire;

use App\Models\UserSession;
use Livewire\Component;

class ConnectedUsers extends Component
{
    public $sessions;

    public function mount()
    {
        $this->sessions = UserSession::with('user')
            ->where('is_active', true)
            ->latest('last_activity')
            ->get();
    }

    public function render()
    {
        return view('livewire.connected-users');
    }
}
