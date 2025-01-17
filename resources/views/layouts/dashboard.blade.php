<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ config('app.name', 'FON IMS') }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link rel="icon" href="{{asset('assets/images/icon.png')}}" sizes="192x192" />
    <link href="{{asset('assets/images/icon.png')}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ url('')}}/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('')}}/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ url('')}}/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ url('')}}/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="{{ url('')}}/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="{{ url('')}}/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.bootstrap5.css">
    <!-- Include Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

    {{--
    <link href="{{ url('')}}/assets/vendor/simple-datatables/style.css" rel="stylesheet"> --}}

    <!-- Template Main CSS File -->
    <link href="{{ url('')}}/assets/css/style.css" rel="stylesheet">
</head>

<body>

    @include('components.navbar')

    @include('components.sidebar')

    <main id="main" class="main">

        @yield('content')

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>FON IMS</span></strong>. All Rights Reserved
        </div>
        <div class="credits">

            Designed by <a href="https://nzivo.github.io">Systems Admin</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <!-- Include Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <!-- Vendor JS Files -->
    <script src="{{ url('')}}/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="{{ url('')}}/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('')}}/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="{{ url('')}}/assets/vendor/echarts/echarts.min.js"></script>
    <script src="{{ url('')}}/assets/vendor/quill/quill.js"></script>
    {{-- <script src="{{ url('')}}/assets/vendor/simple-datatables/simple-datatables.js"></script> --}}
    <script src="{{ url('')}}/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="{{ url('')}}/assets/vendor/php-email-form/validate.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.bootstrap5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>

    <!-- Template Main JS File -->
    <script src="{{ url('')}}/assets/js/main.js"></script>

</body>

</html>