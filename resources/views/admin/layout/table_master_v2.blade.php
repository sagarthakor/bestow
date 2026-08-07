<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        (function () {
            var stored = localStorage.getItem('bestow-theme');
            var theme = stored || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ env('APP_NAME') }} - @yield('title')">
    <title>@yield('title') - {{ Session::get('software_title') ?? env('APP_NAME') }}</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('admin-v2/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/vendor/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/vendor/datatables/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/vendor/datatables/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/vendor/datatables/fixedHeader.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/vendor/select2/select2-bootstrap-5-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/vendor/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-v2/css/tables.css') }}">

    @yield('head')
</head>
<body>

    @include('admin.layout.menu_v2')

        <main class="content-v2">
            <div class="container-fluid">
                @hasSection('breadcrumb')
                    @yield('breadcrumb')
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="footer-v2">
            <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center">
                <span>&copy; {{ now()->format('Y') }} {{ Session::get('software_title') ?? env('APP_NAME') }}. All rights reserved.</span>
                <span class="text-muted text-sm">v2 layout preview</span>
            </div>
        </footer>

    </div><!-- /.main-wrapper-v2 -->
    </div><!-- /.app-shell-v2 -->

    <script src="{{ asset('admin-v2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/datatables/dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/datatables/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/datatables/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('admin-v2/vendor/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('admin-v2/js/theme.js') }}"></script>
    <script src="{{ asset('admin-v2/js/layout.js') }}"></script>
    <script src="{{ asset('admin-v2/js/forms-init.js') }}"></script>
    <script src="{{ asset('admin-v2/js/datatable-init.js') }}"></script>

    @stack('scripts')
</body>
</html>
