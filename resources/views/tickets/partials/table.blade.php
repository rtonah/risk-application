<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Soumis par</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->subject }}</td>
                <td>{{ Str::limit($ticket->description, 60) }}</td>
                <td>
                    {{ $ticket->is_anonymous ? 'Anonyme' : (($ticket->user->first_name ?? '') . ' ' . ($ticket->user->last_name ?? '')) ?? 'N/A' }}
                </td>

                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    {{-- <span class="badge bg-{{ $ticket->status === 'ouvert' ? 'warning' : 'success' }}">
                        {{ ucfirst($ticket->status) }}
                    </span> --}}
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
                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                        Voir
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Aucun ticket trouvé.</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{-- Inclure la pagination si nécessaire --}}
{{ $tickets->links() }}