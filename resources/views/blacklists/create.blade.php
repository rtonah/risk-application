<x-layouts.app>



    {{-- Main --}}

    <title>Conformité - Forms</title>
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="#">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="#">Conformité</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mettre en liste noire</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h2 class="h4">Inscrire sur la liste noire</h2>
                <p class="mb-0">Nous vous prions de prendre connaissance des éléments nécessaires avant d’inscrire un client sur la liste noire d’ACEP Madagascar.</p>
            </div>
            <div>
                <a href="#" class="btn btn-outline-gray" target="_blank"><i class="far fa-question-circle me-1"></i> Forms Docs</a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-6 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">     
                    <form action="{{ route('blacklists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <!-- Form -->
                        <div class="mb-3">
                            <label for="firstName">Nom complet</label>
                            <input type="text" class="form-control is-valid" id="firstName" name="full_name" required>
                            <div class="valid-feedback">
                                Nom et prénom du client!
                            </div>                
                        </div>
                        <!-- End of Form -->
                        <!-- Form -->
                        <div class="mb-3">
                            <label for="firstName">CIN</label>
                            <input
                                type="text"
                                name="national_id"
                                id="national_id"
                                maxlength="15"
                                class="form-control is-valid"
                                required
                                oninput="formatNationalID(this)"
                                placeholder="123 456 789 012"
                            >
                            {{-- <input type="text" class="form-control is-valid" id="firstName" name="national_id" required> --}}

                            <div class="valid-feedback">
                            Numéro de la carte d'indentité du client!
                            </div>                
                        </div>
                        <!-- End of Form -->

                    
                        <!-- Form -->
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Document justificatif (PDF)</label>
                            <input class="form-control" type="file" id="formFile" name="document" accept="application/pdf">
                            <div class="valid-feedback">
                            Fichier PDF seuelement s'il vous plait!
                            </div>  
                        </div>
                        <!-- End of Form -->
                        <!-- Form -->
                        <div class="mb-3">
                            <label for="textarea">Raison de l’ajout</label>
                            <textarea class="form-control" placeholder="Justifier l’ajout..." id="textarea" rows="4" name="reason" required></textarea>
                        </div>
                        <!-- End of Form -->


                        <div class="text-end">
                            <button type="submit" class="btn btn-secondary">Envoyer</button>
                            <a href="{{ route('blacklists.index') }}" class="ml-4 text-gray-600"> 
                                <button class="btn btn-outline-gray-500" type="button">Annuler</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- And Main --}}
    

    {{-- Load JS file --}}
    @push('scripts')
        <script src="{{ asset('js/format-national-id.js') }}"></script>
    @endpush
    {{-- <script src="{{ asset('js/format-national-id.js') }}"></script> --}}
</x-layouts.app>

