{{-- @push('scripts') --}}
    <!-- resources/views/components/sweetalert.blade.php -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('show-delete-confirmation', function (event) {
                Swal.fire({
                    title: 'Confirmer la suppression ?',
                    text: "Cette action est irréversible.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('deleteConfirmed', event.detail.id);
                    }
                });
            });

            window.addEventListener('show-message', function (event) {
                Swal.fire({
                    title: event.detail.title || 'Succès',
                    text: event.detail.text || '',
                    icon: event.detail.type || 'success',
                    timer: 3000,
                    showConfirmButton: false,
                });
            });
        });
    </script>
{{-- @endpush --}}

