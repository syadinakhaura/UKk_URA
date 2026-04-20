<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pengaduan Sarana Sekolah' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 min-h-screen font-sans">
    <div class="flex h-screen overflow-hidden">
        @auth
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out shadow-xl">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-20 bg-slate-800 px-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-500 rounded-lg">
                            <i class="fas fa-bullhorn text-xl"></i>
                        </div>
                        <span class="text-xl font-bold tracking-wider">AURA-UKK</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link route="admin.dashboard" icon="fas fa-chart-line" label="Dashboard" />
                        <x-nav-link route="admin.aspirasi.index" icon="fas fa-clipboard-list" label="Kelola Aspirasi" />
                        <x-nav-link route="admin.siswa.index" icon="fas fa-users" label="Data Siswa" />
                    @elseif(Auth::user()->role === 'yayasan')
                        <x-nav-link route="yayasan.dashboard" icon="fas fa-chart-pie" label="Dashboard" />
                        <x-nav-link route="yayasan.aspirasi.index" icon="fas fa-check-double" label="Validasi" />
                        <x-nav-link route="yayasan.laporan.index" icon="fas fa-file-invoice" label="Laporan" />
                        <x-nav-link route="yayasan.admin.index" icon="fas fa-user-shield" label="Data Admin" />
                    @else
                        <x-nav-link route="siswa.dashboard" icon="fas fa-home" label="Dashboard" />
                    @endif
                </nav>

                <!-- Sidebar Footer -->
                <div class="p-4 bg-slate-800">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-3 text-sm font-medium text-slate-300 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        @endauth

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="h-20 bg-white border-b flex items-center justify-between px-6 lg:px-8 shadow-sm">
                <div class="flex items-center">
                    @auth
                    <button id="sidebarToggle" class="p-2 rounded-md text-slate-600 lg:hidden hover:bg-slate-100 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    @endauth
                    <h2 class="text-xl font-semibold text-slate-800 ml-4 lg:ml-0">
                        @yield('title', 'Aspirasi Sarana Sekolah')
                    </h2>
                </div>

                @auth
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex flex-col items-end mr-2">
                        <span class="text-sm font-bold text-slate-800 leading-none">{{ Auth::user()->name }}</span>
                        <span class="text-xs font-medium text-slate-500 capitalize">{{ Auth::user()->role }}</span>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-indigo-200 shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
                @else
                <div>
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">
                        Login
                    </a>
                </div>
                @endauth
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-slate-50">
                @if(session('success'))
                    <div class="max-w-4xl mx-auto mb-6 flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded shadow-sm" role="alert">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="max-w-4xl mx-auto mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded shadow-sm" role="alert">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle mr-3 text-xl text-rose-500"></i>
                            <p class="font-bold">Terjadi Kesalahan:</p>
                        </div>
                        <ul class="list-disc list-inside text-sm space-y-1 ml-8">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('mousedown', (e) => {
            if (window.innerWidth < 1024 && sidebar && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>
</html>
