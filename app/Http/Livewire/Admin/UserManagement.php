<?php

namespace App\Http\Livewire\Admin;

use App\Models\Branch;
use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithFileUploads;

class UserManagement extends Component
{
    use WithFileUploads;

    public $users;
    public $roles;
    public $branches;
    
    public $selectedRole = '';
    public $selectedStatus = '';
    public $selectedBranch = '';
    public $search = '';

    public $showCreateModal = false;
    public $newUser = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'branch_id' => '',
        'role' => '',
    ];
    public $photo;
    public $selectedUserIdForPasswordReset = null;
    public $newPassword = '';


    #.. Réinitialisation UserManagement
    public function mount()
    {

        $this->roles = Role::all();
        $this->branches = Branch::all(); // ⚠️ Assure-toi que le modèle Branch existe
        $this->loadUsers();
    }

    #.. Fonction filtre
    public function updatedSelectedRole()
    {
        $this->loadUsers();
    }

    public function updatedSelectedStatus()
    {
        $this->loadUsers();
    }

    public function updatedSelectedBranch()
    {
        $this->loadUsers();
    }

    public function updatedSearch()
    {
        $this->loadUsers();
    }

    #.. Affichage des utilisateurs
    public function loadUsers()
    {
        $query = User::with(['roles', 'branch']);

        if ($this->selectedRole) {
            $query->whereHas('roles', fn($q) => $q->where('name', $this->selectedRole));
        }

        if ($this->selectedStatus !== '') {
            $query->where('status', $this->selectedStatus);
        }
        if ($this->selectedBranch !== '') {
            $query->where('branch_id', $this->selectedBranch);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                  ->orWhere('last_name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        $this->users = $query->get();
    }

    #.. Modification rôle
    public function changeUserRole($userId, $newRole)
    {
        $user = User::findOrFail($userId);
        $user->syncRoles([$newRole]);
        $this->mount(); // Refresh data

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Rôle mis à jour.'
        ]);

    }

    #.. Modification statut
    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->status = !$user->status;
        $user->save();
        $this->mount();
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Statut utilisateur mis à jour.'
        ]);
    }

    #.. Création utilisateur
    public function createUser()
    {
        $this->validate([
            'newUser.first_name' => 'required|string|max:255',
            'newUser.last_name' => 'required|string|max:255',
            'newUser.email' => 'required|email|unique:users,email',
            'newUser.branch_id' => 'required|exists:branches,id',
            'newUser.role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|max:1024',
        ]);

        $user = new User();
        $user->first_name = $this->newUser['first_name'];
        $user->last_name = $this->newUser['last_name'];
        $user->email = $this->newUser['email'];
        $user->branch_id = $this->newUser['branch_id'];
        $user->password = bcrypt('@zerty2025');
        $user->status = true;
        $user->must_change_password = true;

        if ($this->photo) {
            $user->profile_photo_path = $this->photo->store('profile-photos', 'public');
        }

        $user->save();

        $user->assignRole($this->newUser['role']);

        $this->reset(['showCreateModal', 'newUser', 'photo']);
        $this->mount();

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Utilisateur créé avec succès.'
        ]);
    }

    #.. Modal de réinitialisation
    public function openPasswordResetModal($userId)
    {
        $this->selectedUserIdForPasswordReset = $userId;
        $this->newPassword = '';
        $this->dispatchBrowserEvent('showPasswordModal');
    }

    #.. Reset Password
    public function resetPassword()
    {
        $this->validate([
            'newPassword' => 'required|min:6',
        ]);

        $user = User::findOrFail($this->selectedUserIdForPasswordReset);
        $user->password = bcrypt($this->newPassword);
        $user->must_change_password = true;
        $user->save();

        $this->dispatchBrowserEvent('hidePasswordModal');

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Mot de passe réinitialisé.'
        ]);
    }


    public function render()
    {
        return view('livewire.admin.user-management');
    }
}
