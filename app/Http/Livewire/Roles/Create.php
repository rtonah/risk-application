<?php

namespace App\Http\Livewire\Roles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Livewire\Component;

class Create extends Component
{
    public string $name = '';
    public array $selectedPermissions = [];

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name',
            'selectedPermissions' => 'array',
        ]);

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        session()->flash('success', 'Rôle créé avec succès !');

        return redirect()->route('roles.index'); // Assurez-vous que cette route existe
    }

    public function permissions()
    {
        return Permission::all();
    }

    public function render()
    {
        return view('livewire.roles.create');
    }
}
