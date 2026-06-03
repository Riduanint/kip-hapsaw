<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPK KIP Kuliah') - AHP & SAW</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Lucide Icons via CDN for modern minimal look -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    @auth
        <div class="dashboard-wrapper">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-logo">
                    <i data-lucide="graduation-cap" style="color: var(--primary); width: 32px; height: 32px;"></i>
                    <h2>SPK <span>KIP</span></h2>
                </div>

                <ul class="sidebar-menu">
                    @if(auth()->user()->isAdmin())
                        <li class="menu-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard', ['sk' => $sk]) }}">
                                <i data-lucide="layout-dashboard"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.criteria') ? 'active' : '' }}">
                            <a href="{{ route('admin.criteria', ['sk' => $sk]) }}">
                                <i data-lucide="list-checks"></i>
                                <span>Kriteria</span>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.ahp') ? 'active' : '' }}">
                            <a href="{{ route('admin.ahp', ['sk' => $sk]) }}">
                                <i data-lucide="scale"></i>
                                <span>Perbandingan AHP</span>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.alternatives') ? 'active' : '' }}">
                            <a href="{{ route('admin.alternatives', ['sk' => $sk]) }}">
                                <i data-lucide="users"></i>
                                <span>Data Pendaftar</span>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.scores') ? 'active' : '' }}">
                            <a href="{{ route('admin.scores', ['sk' => $sk]) }}">
                                <i data-lucide="clipboard-list"></i>
                                <span>Input Nilai</span>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.saw') ? 'active' : '' }}">
                            <a href="{{ route('admin.saw', ['sk' => $sk]) }}">
                                <i data-lucide="award"></i>
                                <span>Perankingan SAW</span>
                            </a>
                        </li>
                    @else
                        <li class="menu-item {{ Route::is('user.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('user.dashboard', ['sk' => $sk]) }}">
                                <i data-lucide="layout-dashboard"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('user.application') ? 'active' : '' }}">
                            <a href="{{ route('user.application', ['sk' => $sk]) }}">
                                <i data-lucide="edit-3"></i>
                                <span>Pengajuan KIP</span>
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="sidebar-footer">
                    <form action="{{ route('logout', ['sk' => $sk]) }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i data-lucide="log-out"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="main-content">
                <!-- Content Header -->
                <header class="content-header">
                    <div>
                        <h1>@yield('header_title')</h1>
                    </div>
                    <div class="user-info-badge">
                        <i data-lucide="user-check" style="width: 16px; height: 16px; color: var(--primary);"></i>
                        <span>{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->role) }})</span>
                    </div>
                </header>

                <!-- Session Alerts -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning">
                        <i data-lucide="alert-triangle" style="width: 20px; height: 20px;"></i>
                        <div>{{ session('warning') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i data-lucide="x-circle" style="width: 20px; height: 20px;"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    @else
        @yield('auth_content')
    @endauth

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
    </script>
</body>
</html>
