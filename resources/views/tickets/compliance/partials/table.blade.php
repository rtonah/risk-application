<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Sujet</th>
            <th>Assigné à</th>
            <th>Statut</th>
            <th>Actions</th>
            <th>Change status</th>
            <th>Détails</th>

        </tr>
    </thead>
    <tbody>
        @forelse($tickets as $ticket)
        <tr>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->subject }}</td>
            <td>{{ $ticket->assignedTo?->first_name ?? 'Non assigné' }}</td>
            <td>
                 @php
                    $statuses = [
                        'open' => 'primary',
                        'in_progress' => 'warning',
                        'escalated' => 'danger',
                        'closed' => 'success',
                    ];
                @endphp

                <span class="badge bg-{{ $statuses[$ticket->status] ?? 'secondary' }}">
                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </span>
            </td>
            <td>
                <!-- Formulaire d'assignation -->
                <form action="{{ route('tickets.compliance.assign', $ticket) }}" method="POST" class="d-flex align-items-center gap-2">
                    @csrf
                    <select name="user_id" class="form-select form-select-sm" style="max-width: 200px;">
                        @foreach($complianceUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->first_name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-gray-500">Assigner</button>
                </form>
            </td>
             <td>
                <!-- Formulaire de changement de statut -->
                <form action="{{ route('tickets.compliance.status', $ticket) }}" method="POST" class="d-flex align-items-center gap-2">
                    @csrf
                    <select name="status" class="form-select form-select-sm" style="max-width: 200px;">
                        <option >{{ ucfirst($ticket->status) }}</option>
                        <option value="in_progress">En cours</option>
                        <option value="escalated">Escaladé à la direction générale</option>
                        <option value="closed">Clôturé</option>
                    </select>
                    <button type="submit" class="btn  btn-sm btn-outline-gray-500">Mettre à jour</button>
                </form>
            </td>
             <td>
                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-outline-tertiary">
                    Voir les détails
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5">Aucun ticket trouvé.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $tickets->links() }}
