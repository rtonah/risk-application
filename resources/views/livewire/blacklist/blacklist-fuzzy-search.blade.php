<div class="row justify-content-center">
    <div class="col-6 mb-4">
        <div class="card border-0 shadow components-section">
            <div class="card-body">     

                <div>
                    <div class="space-y-4">

                       <div>
                            <input type="text" wire:model.debounce.300ms="search" placeholder="Recherche floue (nom, société, ID)..." class="form-control mb-3" />

                            @if($search && count($results))
                                <ul class="list-group">
                                    @foreach($results as $blacklist)
                                        <li class="list-group-item">
                                            <strong>{{ $blacklist->full_name }}</strong>
                                            @if($blacklist->company_name)
                                                — {{ $blacklist->company_name }}
                                            @endif
                                            <br><small>ID : {{ $blacklist->national_id }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif($search && count($results) === 0)
                                <p>Aucun résultat trouvé.</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
