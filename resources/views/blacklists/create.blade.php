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

    <livewire:blacklist.blacklist-form />

    {{-- And Main --}}
    

    {{-- Load JS file --}}
    @push('scripts')
        <script src="{{ asset('js/format-national-id.js') }}"></script>
    @endpush
    {{-- <script src="{{ asset('js/format-national-id.js') }}"></script> --}}
</x-layouts.app>

