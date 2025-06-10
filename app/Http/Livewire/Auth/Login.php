<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
    public $matricule = '';
    public $password = '';
    public $remember_me = false;

    protected $rules = [
        'matricule' => 'required|string',
        'password' => 'required|min:6',
    ];

    public function mount()
    {
        if (auth()->user()) {
            return redirect()->intended('/dashboard');
        }
        $this->fill([
            'matricule' => 'M1114',  // ou ce que tu souhaites comme identifiant par défaut
            'password' => 'secret',
        ]);
    }

    public function login()
    {
        $this->validate();

        $user = User::where('matricule', $this->matricule)->first();

        if ($user && Hash::check($this->password, $user->password)) {
            if ($user->status !== 1) {
                return $this->addError('matricule', 'Votre compte est inactif. Veuillez contacter l\'administrateur.');
            }

            auth()->login($user, $this->remember_me);

            return redirect()->intended('/dashboard');
        }

        return $this->addError('matricule', trans('auth.failed'));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
