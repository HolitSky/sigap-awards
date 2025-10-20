<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Dasboard</li>

                <!-- Hanya menu Beranda -->
                <li>
                    <a href="{{ route('dashboard.index') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Beranda</span>
                    </a>
                </li>

            </ul>

             <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Penilaian Form</li>


                 <li>
                    <a href="{{ route('dashboard.form.bpkh.index') }}" class="waves-effect">
                        <i class="mdi mdi-book-sync"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">BPKH</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('dashboard.form.produsen-dg.index') }}" class="waves-effect">
                        <i class="mdi mdi-book-sync-outline"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">Produsen DG</span>
                    </a>
                </li>


            </ul>

             <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Penilaian Presentasi</li>


                 <li>
                    <a href="{{ route('dashboard.presentation.bpkh.index') }}" class="waves-effect">
                        <i class="mdi mdi-presentation"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">BPKH</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('dashboard.presentation.produsen.index') }}" class="waves-effect">
                        <i class="mdi mdi-presentation-play"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">Produsen DG</span>
                    </a>
                </li>


            </ul>



             <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Penilaian Exhibition / Poster</li>


                 <li>
                    <a href="{{ route('dashboard.exhibition.bpkh.index') }}" class="waves-effect">
                        <i class="mdi mdi-image-multiple"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">BPKH</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('dashboard.exhibition.produsen.index') }}" class="waves-effect">
                        <i class="mdi mdi-image-album"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">Produsen DG</span>
                    </a>
                </li>


            </ul>



            @can('see-admin-menus')
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Settings</li>

                {{-- menu khusus superadmin --}}


                <li>
                    <a href="{{ route('dashboard.user-management.index') }}" class="waves-effect">
                        <i class="mdi mdi-account-cog"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">User Management</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sync-form.index') }}" class="waves-effect">
                        <i class="mdi mdi-cloud-sync"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">Synchronize Form</span>
                    </a>
                </li>

            </ul>
            @endcan


        </div>
    </div>

</div>
