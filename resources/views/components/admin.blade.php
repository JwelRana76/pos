<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Template' }}</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="/css/jquery.dataTables.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" /> --}}
    <link rel="stylesheet" href="/css/bootstrap-select.min.css">
    
    <link rel="stylesheet" type="text/css" href="{{asset('jqueryui/jquery-ui.min.css')}}">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="stylesheet" href="/css/toastr.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    

</head>

<body id="page-top">
    @php
        $setting = setting();
    @endphp
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layout.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layout.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    {{ $slot }}

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" href="login.html">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>


    <!-- Page level custom scripts -->
    <script src="/js/jquery.dataTables.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script> --}}
    <script src="/js/yajradatatable.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script src="{{asset('jqueryui/jquery-ui.min.js')}}" type="text/javascript"></script>

    
    <script src="/js/dataTables.buttons.min.js"></script>
    <script src="/js/buttons.colVis.min.js"></script>
    <script src="/js/vfs_fonts.js"></script>
    <script src="/js/jszip.min.js"></script>
    <script src="/js/buttons.html5.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>

    <script src="/js/dataTables.select.min.js"></script>
    <script src="/js/buttons.print.min.js"></script>
    <!-- Include Toastr CSS and JS via CDN -->
    <script src="/js/bootstrap-select.min.js"></script>
    <script src="/js/toastr.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    {{-- toastr notification section --}}
    <script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true, 
        "timeOut": "2000", 
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"  
    };
</script>
    @if (Session::has('success'))
        <script>
            toastr.success('{{ Session::get('success') }}');
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            toastr.error('{{ Session::get('error') }}');
        </script>
    @endif

    @if (Session::has('warning'))
        <script>
            toastr.warning('{{ Session::get('warning') }}');
        </script>
    @endif

    @if (Session::has('info'))
        <script>
            toastr.info('{{ Session::get('info') }}');
        </script>
    @endif


    @stack('js')
</body>

</html>
