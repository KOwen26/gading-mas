<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gading Mas - Login</title>
    {{-- primary-color: #fe7f2d --}}
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/simple-datatables/style.css">
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    {{-- <link rel="stylesheet" href="assets/css/jquery-ui.min.css"> --}}
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <script src="assets/vendors/simple-datatables/simple-datatables.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Toastify --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"> --}}
</head>
<script src="assets/js/jquery3.6.0.js"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
{{-- <script defer src="{{ asset('assets/js/jquery-ui.js') }}"></script> --}}
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
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-6 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="{{ route('login') }}"><img src="assets/images/logo/logo.jpeg" class="img-fluid"
                                style="object-fit:cover" alt="Logo"></a>
                    </div>
                    <h1 class="auth-title">Gading Mas Unggul</h1>
                    <h5 class="text-black-50 mb-5">Selamat data di Sistem Informasi Gading Mas</h5>
                    @if (session()->has('success') || session()->has('error'))
                        <div class="alert
                    @if (session()->has('success')) alert-success
                    @else
                    alert-danger @endif
                     alert-dismissible fade show"
                            role="alert">
                            {{ session('success') ? session('success') : session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('login.process') }}" method="POST">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" name="user_email" id="email" class="form-control form-control-lg"
                                placeholder="Email" autofocus required ">
                            <div class="  form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" name="user_password" id="password" class="form-control form-control-lg"
                        placeholder="Password" required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                {{-- <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Keep me logged in
                            </label>
                        </div> --}}
                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                </form>
                {{-- <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Don't have an account? <a href="auth-register.html"
                                class="font-bold">Sign
                                up</a>.</p>
                        <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.</p>
                    </div> --}}
            </div>
        </div>
        <div class="col-lg-6 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>

    </div>
</body>
<script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>

</html>
