<?php

namespace App\Http\Livewire\Admin;

use App\Models\Branch;
use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class UserManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $roles, $branches;
    
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

    protected $paginationTheme = 'bootstrap'; // Ou 'tailwind' selon ton interface

    public function mount()
    {
        $this->roles = Role::all();
        $this->branches = Branch::all();
    }

    public function updatedSelectedRole()
    {
        $this->resetPage();
    }

    public function updatedSelectedStatus()
    {
        $this->resetPage();
    }

    public function updatedSelectedBranch()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function changeUserRole($userId, $newRole)
    {
        $user = User::findOrFail($userId);
        $user->syncRoles([$newRole]);

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Rôle mis à jour.'
        ]);
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->status = !$user->status;
        $user->save();

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Statut utilisateur mis à jour.'
        ]);
    }

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
        $this->resetPage();

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Utilisateur créé avec succès.'
        ]);
    }

    public function openPasswordResetModal($userId)
    {
        $this->selectedUserIdForPasswordReset = $userId;
        $this->newPassword = '';
        $this->dispatchBrowserEvent('showPasswordModal');
    }

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

        return view('livewire.admin.user-management', [
            'users' => $query->paginate(5),
        ]);
    }
}
