    <div>
        <h1></h1>

        <table class="table user-table table-hover align-items-center">
            <thead>
                <tr>
                    <th class="border-bottom">ID</th>
                    <th class="border-bottom">Titre</th>
                    <th class="border-bottom">Utilisateur</th>
                    <th class="border-bottom">Date de création</th>
                    <th class="border-bottom">Statut</th>
                    <th class="border-bottom">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseRequests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td class="fw-bold">{{ $request->title }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $request->user->profile_picture ?? asset('assets/img/team/default-profile.png') }}"
                                    class="avatar rounded-circle me-3" alt="Avatar" style="width: 40px; height: 40px;">
                                <div class="d-block">
                                    <span class="fw-bold">{{ $request->user->first_name ?? 'N/A' }} {{ $request->user->last_name ?? '' }}</span>
                                    <div class="small text-gray">{{ $request->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="fw-normal">{{ $request->created_at->translatedFormat('d F Y H:i') }}</span></td>
                        {{-- <td>
                            @php
                                $badgeColor = match($request->status) {
                                    'approved' => 'bg-success',
                                    'refused' => 'bg-danger',
                                    'pending' => 'bg-warning',
                                    default => 'bg-tertiary',
                                };
                            @endphp
                            <span class="badge {{ $badgeColor }}">{{ ucfirst($request->status) }}</span>
                        </td> --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex justify-content-center align-items-center rounded-circle me-3 bg-light text-secondary"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-info-circle" title="Statut de la demande"></i>
                                </div>
                                <div class="d-block">
                                    @php
                                        $badgeColor = match($request->status) {
                                            'approved' => 'bg-success',
                                            'refused' => 'bg-danger',
                                            'pending' => 'bg-warning',
                                            default => 'bg-tertiary',
                                        };
                                    @endphp
                                    <span class="fw-bold badge {{ $badgeColor }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                    @if ($request->status_updated_by)
                                        <div class="small text-gray">Modifié par : {{ $request->user->first_name ?? 'N/A' }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>


                        <td>
                            <a href="{{ route('purchase-requests.review', $request->id) }}" class="btn btn-sm btn-primary">
                                Afficher / Valider
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

