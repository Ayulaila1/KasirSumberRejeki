<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        Login-Cafe Suki
    </title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('backend/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('backend../../images/favicon.png') }}" />
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="loader">
            <div class="cup"></div>
            <div class="handle"></div>
        </div>
        <p class="loader-text">Sedang menyiapkan kopi... ☕</p>
    </div>


    <!-- Background Video -->
    <video autoplay muted loop playsinline class="video-bg">
        <source src="{{ asset('backend/images/videologin.mp4') }}" type="video/mp4">
    </video>

    <!-- Login Box -->
    <div class="login-container">
        <div class="login-box">
            <div class="brand-logo">
                <img src="{{ asset('backend/images/logo.png') }}" alt="logo">
            </div>
            <h4>Welcome back!</h4>
            <h6>Happy to see you again!</h6>
            <form action="/roarr" method="POST">
                @csrf
                <input type="text" name="email" class="form-control" placeholder="Email" required>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <button type="submit" class="btn btn-brown w-100">LOGIN</button>
            </form>
            {{-- <p class="text-center mt-3">Don't have an account? <a href="{{ route('registermiaw') }}"
                    class="auth-link">Register</a>
            </p> --}}
        </div>
    </div>

    <!-- container-scroller -->
    <!-- base:js -->
    <script src="{{ asset('backend/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{ asset('backend/js/off-canvas.js') }}"></script>
    <script src="{{ asset('backend/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('backend/js/template.js') }}"></script>
    <!-- endinject -->

    <!-- Loader Trigger Script -->
    <script>
        window.addEventListener("load", function () {
        setTimeout(() => {
            document.getElementById("preloader").style.display = "none";
        }, 5000); // 5 detik
    });
    </script>
</body>

</html>