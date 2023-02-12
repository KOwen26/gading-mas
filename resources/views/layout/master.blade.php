<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gading Mas Unggul - @yield('title')</title>
    {{-- primary-color: #fe7f2d --}}
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}"> --}}
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/datatables/DataTables-1.11.5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    {{-- <link rel="stylesheet" href="assets/css/jquery-ui.min.css"> --}}
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    {{-- <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script> --}}


    {{-- Online Data Tables --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Toastify --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"> --}}
</head>
<script src="{{ asset('assets/js/jquery3.6.0.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<style>
    .w-5 {
        width: 1.25rem;
    }

    .w-6 {
        width: 1.5rem;
    }

    .select2-selection--single {
        height: 38px !important;
        border: 1px solid #dce7f1 !important;
        color: #607080 !important;
    }

    .select2-selection__rendered {
        color: #607080 !important;
        padding-top: 4px;
        padding-left: 12px !important;
    }

    .select2-selection__arrow {
        margin-top: 4px
    }

</style>

<body>
    <div id="app">
        <x-sidebar />
        <div id="main" class='layout-navbar'>
            <x-navbar />
            <div id="main-content">
                @if (session()->has('success') || session()->has('error'))
                    <div class="alert
                    @if (session()->has('success')) alert-success
                    @else
                    alert-danger @endif
                    alert-dismissible fade show"
                        role="alert">
                        {{ session('success') ? session('success') : session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="modal fade text-left w-100" id="modal-detail-lg" tabindex="-1" role="dialog"
                    aria-labelledby="product-modal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>
                @yield('content')
                <footer>
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2022 &copy; PlanetWeb</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with <span class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span>
                                by
                                <a href="https://planetweb.id">
                                    PlanetWeb
                                </a>
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
@stack('script')
{{-- Toastify --}}
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    Toastify({
        text: "This is a toast",
        duration: 3000,
        destination: "https://github.com/apvarun/toastify-js",
        newWindow: true,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "right", // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
            background: "linear-gradient(to right, #00b09b, #96c93d)",
        },
        onClick: function() {} // Callback after click
    }).showToast();
</script> --}}

</html>
