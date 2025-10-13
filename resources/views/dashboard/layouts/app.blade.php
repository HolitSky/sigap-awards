<!doctype html>
<html lang="en">


<head>

        <meta charset="utf-8" />
        <title>Dashboard | Sigap Award</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('/sigap-assets/images/favicon.ico') }}">

        <base href="{{ url('/') }}/">

        <!-- DataTables -->
        <link href="{{ asset('/dashboard-assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/dashboard-assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="{{ asset('/dashboard-assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{ asset('/dashboard-assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('/dashboard-assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('/dashboard-assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/dashboard-assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App js -->
        <script src="{{ asset('/dashboard-assets/js/plugin.js') }}"></script>

        @stack('styles')
    </head>

    <body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">


            <!-- start header -->
            @include('dashboard.layouts.header')
            <!-- end header -->

            <!-- ========== Left Sidebar Start ========== -->
            @include('dashboard.layouts.navigation')
            <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

            <div class="main-content">
                <!-- start content -->
                @yield('content')
                <!-- end content -->




                <!-- start footer -->
                @include('dashboard.layouts.footer')
                <!-- end footer -->
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div data-simplebar class="h-100">
                <div class="rightbar-title d-flex align-items-center px-3 py-4">

                    <h5 class="m-0 me-2">Settings</h5>

                    <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>

                <!-- Settings -->
                <hr class="mt-0" />
                <h6 class="text-center mb-0">Choose Layouts</h6>

                <div class="p-4">
                    <div class="mb-2">
                        <img src="{{ asset('dashboard-assets/images/layouts/layout-1.jpg') }}" class="img-thumbnail" alt="layout images">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" checked>
                        <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                    </div>

                    <div class="mb-2">
                        <img src="{{ asset('dashboard-assets/images/layouts/layout-2.jpg') }}" class="img-thumbnail" alt="layout images">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch">
                        <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                    </div>

                    <div class="mb-2">
                        <img src="{{ asset('dashboard-assets/images/layouts/layout-3.jpg') }}" class="img-thumbnail" alt="layout images">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="rtl-mode-switch">
                        <label class="form-check-label" for="rtl-mode-switch">RTL Mode</label>
                    </div>

                    <div class="mb-2">
                        <img src="{{ asset('dashboard-assets/images/layouts/layout-4.jpg') }}" class="img-thumbnail" alt="layout images">
                    </div>
                    <div class="form-check form-switch mb-5">
                        <input class="form-check-input theme-choice" type="checkbox" id="dark-rtl-mode-switch">
                        <label class="form-check-label" for="dark-rtl-mode-switch">Dark RTL Mode</label>
                    </div>


                </div>

            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('dashboard-assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/node-waves/waves.min.js') }}"></script>



        <!-- Required datatable js -->
        <script src="{{ asset('dashboard-assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
         <!-- Buttons examples -->
         <script src="{{ asset('dashboard-assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
         <script src="{{ asset('dashboard-assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
         <script src="{{ asset('dashboard-assets/libs/jszip/jszip.min.js') }}"></script>
         <script src="{{ asset('dashboard-assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
         <script src="{{ asset('dashboard-assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
         <script src="{{ asset('dashboard-assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
         <script src="{{ asset('dashboard-assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
         <script src="{{ asset('dashboard-assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>


        <!-- Responsive examples -->
        <script src="{{ asset('dashboard-assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <!-- Datatable init js -->
        <script src="{{ asset('dashboard-assets/js/pages/datatables.init.js') }}"></script>

        <!-- apexcharts -->
        <script src="{{ asset('dashboard-assets/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- dashboard init -->
        <script src="{{ asset('dashboard-assets/js/pages/dashboard.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('dashboard-assets/js/app.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <script>
        $(document).ready(function() {
            // Check for success message (login/logout)

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('auth_action') === 'logout' ? 'Logout Berhasil!' : 'Login Berhasil!' }}',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    timerProgressBar: true
                });
            @endif

            // Logout confirmation via SweetAlert2 (bind to logout link click)
            $(document).on('click', '#logout-link', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Apakah Anda yakin ingin logout?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Logout!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit the form
                        document.getElementById('logout-form').submit();
                    }
                });
            });
        });
        </script>

        @stack('scripts')
    </body>


</html>
