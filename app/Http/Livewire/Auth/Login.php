<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;

class Login extends Component
{

    public $email = '';
    public $password = '';
    public $remember_me = false;

    protected $rules = [
        'email' => 'required|email:rfc,dns',
        'password' => 'required|min:6',
    ];

    //This mounts the default credentials for the admin. Remove this section if you want to make it public.
    public function mount()
    {
        if (auth()->user()) {
            return redirect()->intended('/dashboard');
        }
        $this->fill([
            'email' => 'admin@volt.com',
            'password' => 'secret',
        ]);
    }

    // public function login()
    // {
    //     $credentials = $this->validate();
    //     if (auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
    //         $user = User::where(['email' => $this->email])->first();
    //         auth()->login($user, $this->remember_me);
    //         return redirect()->intended('/dashboard');
    //     } else {
    //         return $this->addError('email', trans('auth.failed'));
    //     }
    // }

    public function login()
    {
        $credentials = $this->validate();

        $user = User::where('email', $this->email)->first();

        // Vérifie que l'utilisateur existe et que le mot de passe est correct
        if ($user && \Hash::check($this->password, $user->password)) {
            // Vérifie le statut
            if ($user->status !== 1) {
                return $this->addError('email', 'Votre compte est inactif. Veuillez contacter l\'administrateur.');
            }

            // Authentification réussie
            auth()->login($user, $this->remember_me);
            return redirect()->intended('/dashboard');
        }

        return $this->addError('email', trans('auth.failed'));
    }


    public function render()
    {
        return view('livewire.auth.login');
    }
}
