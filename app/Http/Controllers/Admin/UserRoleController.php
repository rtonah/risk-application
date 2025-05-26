<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;


class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    #.. Update de l'utilisateurs
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'gender'  => 'required|string|max:255',
            'city'  => 'required|string|max:255',
            'number'  => 'required|string|max:255',
        ]);

        $user->update($request->only('first_name', 'last_name', 'email', 'gender', 'address', 'number', 'city', 'ZIP'));

        return redirect()->route('admin.users.edit', $user)->with('success', 'Profil mis à jour avec succès.');
    }

    #.. Update de l'utilisateurs
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.edit', $user)
            ->with('success', 'Mot de passe mis à jour avec succès.');
    }

    public function roles()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('users', 'roles', 'permissions'));
    }

    public function updateRoles(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'Role updated successfully.');
    }

    public function getPermissions(Role $role)
    {
        return response()->json($role->permissions);
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'number' => $request->number,
        ]);

        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'User created successfully!');
    }

    public function roles_store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array|nullable',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->back()->with('success', 'Rôle créé avec succès.');
    }

    #.. Fonction pour désactivé l'utisateurs
    public function deactivate(User $user)
    {
        $user->update(['status' => 0]); // Ou autre logique de désactivation
        return redirect()->back()->with('success', 'Utilisateur désactivé.');
    }

    #.. Modification users et rôles
    public function edit(User $user)
    {
        $roles = Role::all(); // Si tu veux permettre la modification du rôle aussi
        return view('admin.users.edit', compact('user', 'roles'));
    }


}
