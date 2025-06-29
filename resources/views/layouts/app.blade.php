<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Kasir App')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('asset_offline/style.css') }}">

    @livewireStyles
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3 position-fixed" id="sidebar">
        <h4 class="text-white mb-4">Kasir App</h4>
        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i> <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="bi bi-box-seam"></i> <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="bi bi-cash-stack"></i> <span>Penjualan</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="bi bi-graph-up"></i> <span>Laporan</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-white text-start px-0">
                        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content" id="content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-outline-light d-md-inline d-inline bg-primary border-0" id="toggleSidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h2 class="mb-0">@yield('title')</h2>
        </div>

        @yield('content')
    </div>

    @livewireScripts
    @livewireAlertScripts

    <!-- Custom Script -->
    <script src="{{ asset('asset_offline/script.js') }}"></script>
</body>

</html>