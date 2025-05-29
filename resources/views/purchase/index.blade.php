<x-layouts.app>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Achat</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Commander</li>
                </ol>
            </nav>
            <h2 class="h4">📄 Liste des demandes d'achat</h2>
            <p class="mb-0">Cliquez sur le bouton "Valider" pour approuver ou rejeter la demande d'achat..</p>
        </div>
    </div>
    {{-- Appel Livewire list demande d'achat     --}}
    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <div class="d-flex mb-3">
            <select class="form-select fmxw-200" aria-label="Message select example">
                <option selected>Bulk Action</option>
                <option value="1">Send Email</option>
                <option value="2">Change Group</option>
                <option value="3">Delete User</option>
            </select>
            <button class="btn btn-sm px-3 btn-secondary ms-3">Apply</button>
        </div>
        <livewire:purchase.purchase-request-list />
    </div>
</x-layouts.app>

