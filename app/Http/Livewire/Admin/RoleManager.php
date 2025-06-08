<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Collection;

class RoleManager extends Component
{
    public $roles = [];
    public $selectedRoleId = null;

    public $allPermissions = [];
    public $selectedPermissions = [];

    public function mount()
    {
        $this->roles = Role::all();
        $this->allPermissions = Permission::all();
    }

    public function selectRole($roleId)
    {
        $this->selectedRoleId = $roleId;

        $role = Role::findById($roleId);

        // On récupère les permissions du rôle sous forme de tableau d'ids pour cocher les checkbox
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
    }

    public function updatePermissions()
    {
        $role = Role::findById($this->selectedRoleId);

        // Mauvais : ça passe un tableau d'IDs directement
        // $role->syncPermissions($this->selectedPermissions);

        // ✅ Corriger en récupérant les objets Permission correspondants :
        $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

        $role->syncPermissions($permissions);


        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Permissions mises à jour avec succès.'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.role-manager');
    }
}
