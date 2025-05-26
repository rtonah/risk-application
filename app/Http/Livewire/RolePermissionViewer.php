<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RolePermissionViewer extends Component
{
    public $roles = [];
    public $selectedRoleId = null;
    public $permissions = [];

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function selectRole($roleId)
    {
        $this->selectedRoleId = $roleId;
        $role = Role::findById($roleId);
        $this->permissions = $role->permissions;
    }

    public function render()
    {
        return view('livewire.role-permission-viewer');
    }
}
