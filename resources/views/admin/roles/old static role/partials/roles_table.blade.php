<table class="table user-table table-hover align-items-center">
        <thead>
            <tr>
                <th class="border-bottom">
                    <div class="form-check dashboard-check">
                        <input class="form-check-input" type="checkbox" value="" id="userCheck55">
                        <label class="form-check-label" for="userCheck55">
                        </label>
                    </div>
                </th>
                <th class="border-bottom">Name</th>
                <th class="border-bottom">Role</th>
                <th class="border-bottom">Date Created</th>
                <th class="border-bottom">Status</th>
                <th class="border-bottom">Change Role</th>
                <th class="border-bottom">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        <div class="form-check dashboard-check">
                            <input class="form-check-input" type="checkbox" value="" id="userCheck1">
                            <label class="form-check-label" for="userCheck1">
                            </label>
                        </div>
                    </td>
                    <td>
                        <a href="#" class="d-flex align-items-center">
                            <img src="../assets/img/team/profile-picture-1.jpg" class="avatar rounded-circle me-3"
                                alt="Avatar">
                            <div class="d-block">
                                <span class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</span>
                                <div class="small text-gray">{{ $user->email }}</div>
                            </div>
                        </a>
                    </td>
                    <td><span class="fw-normal">{{ $user->roles->pluck('name')->first() ?? 'None' }}</span></td>
                    <td><span class="fw-normal d-flex align-items-center">{{ $user->created_at->translatedFormat('d F Y') }}</span></td>
                    <td><span class="fw-normal text-success">Active</span></td>
                    <td class="p-3">
                        <form method="POST" action="{{ route('admin.users.updateRoles', $user) }}" class="d-flex align-items-center gap-2">
                            @csrf
                            <select name="role" class="form-select form-select-sm" style="max-width: 200px;">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Mettre à jour</button>
                        </form>
                    </td>
                    <td>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>