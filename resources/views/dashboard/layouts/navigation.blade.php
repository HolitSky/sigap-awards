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
                        <i class="bx bx-spreadsheet"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">BPKH</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('dashboard.form.produsen-dg.index') }}" class="waves-effect">
                        <i class="bx bx-file"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">Produsen DG</span>
                    </a>
                </li>

                @can('see-admin-menus')
                {{-- menu khusus superadmin --}}
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="bx bxs-eraser"></i>
                        {{-- <span class="badge rounded-pill bg-danger float-end">10</span> --}}
                        <span key="t-forms">Menu Test</span>
                    </a>
                </li>
                @endcan
            </ul>


        </div>
    </div>

</div>
