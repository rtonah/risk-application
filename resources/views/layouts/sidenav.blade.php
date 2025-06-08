<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    <div class="sidebar-inner px-2 pt-3">
      
        <ul class="nav flex-column pt-3 pt-md-0">
            <li class="nav-item {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
                <a href="{{route('dashboard')}}" class="nav-link">
                    <span class="sidebar-icon"> <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg></span></span>
                    <span class="sidebar-text">Dashboard</span>

                </a>
            </li>
            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>

            {{-- Menu blacklist  --}}
            @can('lookup_blacklist')
                <li class="nav-item">
                    <span
                        class="nav-link {{ Request::is('blacklists*') ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-blacklist" aria-expanded="{{ Request::is('blacklists*') ? 'true' : 'false' }}" aria-controls="submenu-blacklist">
                        <span>
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                    </path>
                                </svg>
                            </span>
                            <span class="sidebar-text">Blacklists</span>
                        </span>
                        <span class="link-arrow">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </span>

                    <div class="multi-level collapse {{ Request::is('blacklists*') ? 'show' : '' }}" id="submenu-blacklist">
                        <ul class="flex-column nav">
                            @can('can_update_blacklist')
                                {{-- Lien vers la liste --}}
                                <li class="nav-item {{ Request::is('blacklists') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('blacklists.index') }}">
                                        <span class="sidebar-text">Liste blacklist</span>
                                    </a>
                                </li>

                                {{-- Lien vers création --}}
                                <li class="nav-item {{ Request::is('blacklists/create') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('blacklists.create') }}">
                                        <span class="sidebar-text">Ajouter une entrée</span>
                                    </a>
                                </li>
                            @endcan

                            {{-- Lien vers rapports --}}
                            <li class="nav-item {{ Request::is('blacklists/reports') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('blacklists.search') }}">
                                    <span class="sidebar-text">Recherche</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan
            
            {{-- Menu Mobile Money Taratra --}}
            @can('execute_payroll')
                <li class="nav-item">
                    <span
                        class="nav-link {{ Request::is('mvola*') ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-mvola" aria-expanded="{{ Request::is('mvola*') ? 'true' : 'false' }}" aria-controls="submenu-mvola">
                        <span>
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </span>
                            <span class="sidebar-text">Taratra</span>
                        </span>
                        <span class="link-arrow">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>

                    <div class="multi-level collapse {{ Request::is('taratra*') ? 'show' : '' }}" id="submenu-mvola">
                        <ul class="flex-column nav">
                            @can('execute_momo')
                                <li class="nav-item {{ Request::is('taratra/mvola/create') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('mvola.create') }}">
                                      <span class="sidebar-text">MOMO transaction</span>
                                    </a>
                                </li>
                            @endcan
                            @can('verify_payment_report')
                                <li class="nav-item {{ Request::is('taratra/mvola/repports') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('mvola.repports') }}">
                                      <span class="sidebar-text">MOMO Rapports</span>
                                    </a>
                                </li>
                            @endcan    
                        </ul>
                    </div>
                </li>
            @endcan
            {{-- Menu réclamation --}}
            @can('create_claim')
                <li class="nav-item">
                    <span
                        class="nav-link {{ Request::is('tickets*') ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-tickets">
                        <span>
                          <span class="sidebar-icon">
                            <!-- Icône bouclier pour conformité -->
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd"
                                    d="M10 2a1 1 0 01.894.553l5 10A1 1 0 0115 14H5a1 1 0 01-.894-1.447l5-10A1 1 0 0110 2zm0 3.618L6.618 12h6.764L10 5.618z"
                                    clip-rule="evenodd"/>
                            </svg>
                          </span>
                          <span class="sidebar-text">Réclamation</span>
                        </span>
                        <span class="link-arrow">
                          <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"></path>
                          </svg>
                        </span>
                    </span>
                    <div class="multi-level collapse {{ Request::is('tickets*') ? 'show' : '' }}" id="submenu-tickets">

                        <ul class="flex-column nav">
                            <li class="nav-item {{ Request::is('tickets/create*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('tickets.create') }}">
                                    <span class="sidebar-text">Création réclamations</span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is('tickets/me*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('tickets.me') }}">
                                    <span class="sidebar-text">Mes réclamations</span>
                                </a>
                            </li>
                            @can('followup_claims')
                                <li class="nav-item {{ Request::is('tickets') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('tickets.index') }}">
                                        <span class="sidebar-text">Réclamations</span>
                                    </a>
                                </li>
                            @endcan
                            @can('track_claims')
                                <li class="nav-item {{ Request::is('tickets_gestion*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('tickets.compliance.index') }}">
                                        <span class="sidebar-text">Gestion Reclamation</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>            
                </li>
            @endcan

            @can('verify_grace_period')
                <li class="nav-item">
                    <span
                      class="nav-link {{ Request::is('musoni*') ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
                      data-bs-toggle="collapse" data-bs-target="#submenu-musoni">
                      <span>
                        <span class="sidebar-icon">
                          <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                              d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                          </svg>
                        </span>
                        <span class="sidebar-text">Musoni</span>
                      </span>
                      <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                      </span>
                    </span>

                    <div class="multi-level collapse {{ Request::is('musoni*') ? 'show' : '' }}" id="submenu-musoni">
                      <ul class="flex-column nav">
                        <li class="nav-item {{ Request::is('musoni_grace*') ? 'active' : '' }}">
                          <a class="nav-link" href="{{ route('grace.index') }}">
                            <span class="sidebar-text">Vérification grace</span>
                          </a>
                        </li>

                        <!-- Nouveau sous-menu Virement -->
                        
                      </ul>
                    </div>
                </li>
            @endcan

            {{-- Gestion d'achat --}}
            @canany(['create_purchase_request', 'approve_purchase_request', 'process_purchase_request'])
                <li class="nav-item">
                  <span
                      class="nav-link {{ Request::is('purchase*') ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
                      data-bs-toggle="collapse" data-bs-target="#submenu-achat">
                      <span>
                          <span class="sidebar-icon">
                              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                  d="M3 3a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V3zM3 8h14v9a1 1 0 01-1 1H4a1 1 0 01-1-1V8z" />
                              </svg>
                          </span>
                          <span class="sidebar-text">Achat</span>
                      </span>
                      <span class="link-arrow">
                          <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"></path>
                          </svg>
                      </span>
                  </span>

                  <div class="multi-level collapse {{ Request::is('purchase-requests*') ? 'show' : '' }}" id="submenu-achat">
                      <ul class="flex-column nav">
                          @can('create_purchase_request')
                              {{-- Lien vers la création de demande --}}
                              <li class="nav-item {{ Request::is('purchase-requests/create') ? 'active' : '' }}">
                                  <a class="nav-link" href="{{ route('purchase-requests.create') }}">
                                      <span class="sidebar-text">Nouvelle demande</span>
                                  </a>
                              </li>

                              {{-- Lien vers les demandes de l'utilisateur connecté --}}
                              <li class="nav-item {{ Request::is('purchase-requests/mes-demandes') ? 'active' : '' }}">
                                  <a class="nav-link" href="{{ route('purchase-requests.mine') }}">
                                      <span class="sidebar-text">Mes demandes d'achat</span>
                                  </a>
                              </li>
                          @endcan
                            
                          @can('process_purchase_request')
                              {{-- Lien vers la liste des demandes --}}
                              <li class="nav-item {{ Request::is('purchase-requests') ? 'active' : '' }}">
                                  <a class="nav-link" href="{{ route('purchase-requests.index') }}">
                                      <span class="sidebar-text">Liste des demandes</span>
                                  </a>
                              </li>
                          @endcan

                      </ul>
                  </div>

                </li>
            @endcanany

            {{-- Transaction salariale --}}
            @can('do_transaction')
                <li class="nav-item {{ Request::segment(1) == 'salary-payments' ? 'active' : '' }}">
                    <a href="{{ route('salary-payments.index') }}" class="nav-link d-flex justify-content-between">
                        <span>
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                      d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                    </path>
                                </svg>
                            </span>
                            <span class="sidebar-text">Virement salarial </span>
                        </span>
                    </a>
                </li>
            @endcan

          
            {{-- Gestion utilisateurs --}}
            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>
            @can('create_user')
              <li class="nav-item {{ Request::is('admin/users') ? 'active' : '' }}">
                  <a href="{{ route('admin.users.index') }}" class="nav-link d-flex justify-content-between">
                      <span>
                          <span class="sidebar-icon">
                              <!-- Icône pour utilisateurs : Icône "user group" -->
                              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13 7a3 3 0 11-6 0 3 3 0 016 0zM4 14s1-1 5-1 5 1 5 1v1H4v-1z"></path>
                              </svg>
                          </span>
                          <span class="sidebar-text">Utilisateurs</span>
                      </span>
                  </a>
              </li>
            @endcan

            {{-- Partie Administrateurs --}}
            @role('admin')
                <li class="nav-item {{ Request::segment(1) == 'roles' ? 'active' : '' }}">
                    <a href="{{ route('roles.index') }}" class="nav-link d-flex justify-content-between">
                        <span>
                            <span class="sidebar-icon">
                                <!-- Icône pour rôles : Icône "key" -->
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd" d="M18 8a6 6 0 11-11.472 2.797L2.293 15.03a1 1 0 001.414 1.415l1.293-1.293L6 16l2-2-1.707-1.707 1.293-1.293A6 6 0 0118 8zm-3 0a3 3 0 11-6 0 3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <span class="sidebar-text">Roles</span>
                        </span>
                    </a>
                </li>

                <li class="nav-item {{ Request::is('admin/cbs-config') ? 'active' : '' }}">
                    <a href="{{ route('admin.cbs-config') }}" class="nav-link d-flex justify-content-between">
                        <span>
                            <span class="sidebar-icon">
                                <!-- Icône pour rôles : Icône "key" -->
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                    d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                                    clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <span class="sidebar-text">CBS .env</span>
                        </span>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}"
                        class="btn btn-secondary d-flex align-items-center justify-content-center btn-upgrade-pro">
                        <span class="sidebar-icon d-inline-flex align-items-center justify-content-center">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd"
                                d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                                clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span>Admin Pannel</span>
                    </a>
                </li>
            @endrole
        </ul>
    </div>
</nav>