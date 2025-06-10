<x-layouts.base>
    {{-- @if(in_array(request()->route()->getName(), ['dashboard', 'profile', 'profile-example', 'users', 'bootstrap-tables', 'transactions',
        'buttons','forms', 'modals', 'notifications', 'typography', 'upgrade-to-pro', 'blacklists.index', 'blacklists.create', 'blacklists.edit', 'blacklists.show'])) --}}
    @if(Str::startsWith(request()->route()->getName(), [
        'dashboard', 'blacklists', 'admin', 'tickets', 'roles', 'create', 'musoni', 'grace', 'setting', 'salary-payments', 'purchase-request','mvola', 'incidents', 'risque'
    ]))

            {{-- Nav --}}
            @include('layouts.nav')
            {{-- SideNav --}}
            @include('layouts.sidenav')
            <main class="content">
                {{-- TopBar --}}
                @include('layouts.topbar')
                {{ $slot }}
                {{-- Footer --}}
                @include('layouts.footer')
            </main>

        @elseif(in_array(request()->route()->getName(), ['register', 'register-example', 'login', 'login-example',
            'forgot-password', 'forgot-password-example', 'reset-password','reset-password-example']))

            {{ $slot }}
            {{-- Footer --}}
            @include('layouts.footer2')


        @elseif(in_array(request()->route()->getName(), ['404', '500', 'lock']))

        {{ $slot }}
    
        @else @include('404')

    @endif
    @stack('scripts')

    {{-- Sweet Alert --}}
    @include('components.sweetalert')
    
     <script>
        window.addEventListener('hide-import-modal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
            if (modal) modal.hide();
        });
    </script>
</x-layouts.base>