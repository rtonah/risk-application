<div class="card p-4">
    <form wire:submit.prevent="save">
        <!-- Champ Nom du rôle -->
        <div class="mb-4">
            <label for="name" class="form-label">Nom du rôle</label>
            <input type="text" id="name" wire:model="name" class="form-control" placeholder="Entrez le nom du rôle">
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Sélecteur de permissions -->
        <div class="mb-4">
            <label for="permissions" class="form-label">Permissions</label>
            <select wire:model="selectedPermissions" id="permissions" class="form-control" multiple>
                @foreach($this->permissions() as $permission)
                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                @endforeach
            </select>
            @error('selectedPermissions') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Bouton de soumission -->
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Créer</button>
        </div>
    </form>
</div>
