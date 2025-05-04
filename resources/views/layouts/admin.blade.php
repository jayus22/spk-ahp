<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Sistem Perbandingan Laptop</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .content-wrapper {
            min-height: calc(100vh - 150px);
            padding: 20px 0;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
        }

        .sidebar {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 5px;
        }

        .sidebar .nav-link {
            color: #333;
            padding: 8px 15px;
            border-radius: 4px;
        }

        .sidebar .nav-link:hover {
            background-color: #e9ecef;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }
    </style>
    @yield('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">SistemLaptop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/user/dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/results/history') }}">Riwayat Hasil</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{-- {{ Auth::user()->name }} --}}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ url('/profile') }}">Profil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Keluar
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper container">
        <div class="row">
            <div class="col-md-3">
                <div class="sidebar mb-4">
                    <h5 class="mb-3">Menu Pengguna</h5>
                    <div class="nav flex-column">
                        <a href="{{ url('/user/dashboard') }}"
                            class="nav-link {{ Request::is('user/dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="{{ url('/user/decision-form') }}"
                            class="nav-link {{ Request::is('user/decision-form') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list me-2"></i> Form Keputusan
                        </a>
                        <a href="{{ url('/results/history') }}"
                            class="nav-link {{ Request::is('results/history') ? 'active' : '' }}">
                            <i class="fas fa-history me-2"></i> Riwayat Hasil
                        </a>
                        <a href="{{ url('/profile') }}" class="nav-link {{ Request::is('profile') ? 'active' : '' }}">
                            <i class="fas fa-user me-2"></i> Profil
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">@yield('title')</h3>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Sistem Perbandingan Laptop. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    @yield('scripts')
</body>

</html>
