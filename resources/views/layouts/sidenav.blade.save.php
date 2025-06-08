<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    <div class="sidebar-inner px-2 pt-3">
        <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-4">
                    <img src="/assets/img/team/profile-picture-3.jpg" class="card-img-top rounded-circle border-white"
                    alt="Bonnie Green">
                </div>
                <div class="d-block">
                    <h2 class="h5 mb-3">Hi, Jane</h2>
                    <a href="/login" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
                        <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Sign Out
                    </a>
                </div>
            </div>
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
                aria-expanded="true" aria-label="Toggle navigation">
                <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
                </svg>
                </a>
            </div>
        </div>
      <ul class="nav flex-column pt-3 pt-md-0">
          <li class="nav-item {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
              <a href="/dashboard" class="nav-link">
                  <span class="sidebar-icon">
                      <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                          <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                      </svg>
                  </span>
                  <span class="sidebar-text">Dashboard</span>
              </a>
          </li>

          <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>

            {{-- Blacklist --}}
            @canany(['view_blacklist', 'create_blacklist', 'lookup_blacklist'])
                <li class="nav-item">
                    <span class="nav-link {{ Request::is('blacklists*') ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
                            data-bs-toggle="collapse" data-bs-target="#submenu-blacklist"
                            aria-expanded="{{ Request::is('blacklists*') ? 'true' : 'false' }}"
                            aria-controls="submenu-blacklist">
                        <span>
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5z..." />
                                </svg>
                            </span>
                            <span class="sidebar-text">Blacklists</span>
                        </span>
                        <span class="link-arrow">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4..."
                                        clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </span>

                    <div class="multi-level collapse {{ Request::is('blacklists*') ? 'show' : '' }}" id="submenu-blacklist">
                        <ul class="flex-column nav">
                            @can('view_blacklist')
                                <li class="nav-item {{ Request::is('blacklists') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('blacklists.index') }}">
                                        <span class="sidebar-text">Liste</span>
                                    </a>
                                </li>
                            @endcan
                            @can('create_blacklist')
                            <li class="nav-item {{ Request::is('blacklists/create') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('blacklists.create') }}">
                                    <span class="sidebar-text">Ajouter une entrée</span>
                                </a>
                            </li>
                            @endcan
                            @can('lookup_blacklist')
                            <li class="nav-item {{ Request::is('blacklists/reports') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('blacklists.search') }}">
                                    <span class="sidebar-text">Recherche</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            {{-- Taratra / Mvola --}}
            @canany(['view_mvola', 'create_mvola', 'view_mvola_reports'])
                <li class="nav-item">
                <span class="nav-link {{ Request::is('mvola*') ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-mvola"
                        aria-expanded="{{ Request::is('mvola*') ? 'true' : 'false' }}"
                        aria-controls="submenu-mvola">
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="..." />
                            </svg>
                        </span>
                        <span class="sidebar-text">Taratra</span>
                    </span>
                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="..." clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>

                <div class="multi-level collapse {{ Request::is('taratra*') ? 'show' : '' }}" id="submenu-mvola">
                    <ul class="flex-column nav">
                        @can('view_mvola')
                        <li class="nav-item {{ Request::is('mvola') ? 'active' : '' }}">
                            <a class="nav-link" href="#">
                                <span class="sidebar-text">Airtel transaction</span>
                            </a>
                        </li>
                        @endcan
                        @can('create_mvola')
                        <li class="nav-item {{ Request::is('taratra/mvola/create') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('mvola.create') }}">
                                <span class="sidebar-text">MVola transaction</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_mvola_reports')
                        <li class="nav-item {{ Request::is('taratra/mvola/repports') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('mvola.repports') }}">
                                <span class="sidebar-text">Mvola Rapports</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
                </li>
            @endcanany

          {{-- Tickets / Réclamations --}}
          @canany(['view_ticket', 'assign_ticket', 'manage_ticket_status'])
          <li class="nav-item">
              <span class="nav-link {{ Request::is('tickets*') ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#submenu-tickets"
                    aria-expanded="{{ Request::is('tickets*') ? 'true' : 'false' }}"
                    aria-controls="submenu-tickets">
                  <span>
                      <span class="sidebar-icon">
                          <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="..." clip-rule="evenodd"></path>
                          </svg>
                      </span>
                      <span class="sidebar-text">Tickets</span>
                  </span>
                  <span class="link-arrow">
                      <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="..." clip-rule="evenodd" />
                      </svg>
                  </span>
              </span>

              <div class="multi-level collapse {{ Request::is('tickets*') ? 'show' : '' }}" id="submenu-tickets">
                  <ul class="flex-column nav">
                      @can('view_ticket')
                      <li class="nav-item {{ Request::is('tickets') ? 'active' : '' }}">
                          <a class="nav-link" href="{{ route('compliance.tickets.index') }}">
                              <span class="sidebar-text">Liste des tickets</span>
                          </a>
                      </li>
                      @endcan
                      @can('assign_ticket')
                      <li class="nav-item {{ Request::is('tickets/assign') ? 'active' : '' }}">
                          <a class="nav-link" href="{{ route('compliance.tickets.assign') }}">
                              <span class="sidebar-text">Affectation</span>
                          </a>
                      </li>
                      @endcan
                      @can('manage_ticket_status')
                      <li class="nav-item {{ Request::is('tickets/status') ? 'active' : '' }}">
                          <a class="nav-link" href="{{ route('compliance.tickets.status') }}">
                              <span class="sidebar-text">Statut</span>
                          </a>
                      </li>
                      @endcan
                  </ul>
              </div>
          </li>
          @endcanany
      </ul>
  </div>
</nav>
