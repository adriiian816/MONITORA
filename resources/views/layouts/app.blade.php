<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MONITORA') }}</title>

    <!-- Fonts & FontAwesome Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- AlpineJS & SweetAlert2 -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-100 text-slate-800">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: true }">

     <!-- SIDEBAR NAVIGASI -->
     <aside
        :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="bg-slate-900 text-slate-300 flex flex-col transition-all duration-300 ease-in-out min-h-screen z-30 shadow-xl">
        
        <!-- Logo & Toggle Sidebar -->
        <div class="h-16 flex items-center justify-between px-4 bg-slate-950 border-b border-slate-800">
            <div class="flex items-center gap-3 overflow-hidden">
                <!-- Gambar Logo MONITORA (Tetap Tampil Saat Sidebar Diciutkan) -->
                <img src="{{ asset('tailadmin/images/logo-monitora.png') }}" 
                     alt="MONITORA Logo" 
                     class="w-8 h-8 object-contain drop-shadow-sm flex-shrink-0">
                
                <!-- Nama Aplikasi (Hanya Tampil Saat Sidebar Terbuka) -->
                <span x-show="sidebarOpen" class="font-bold text-base text-white tracking-wide whitespace-nowrap">
                    MONITORA
                </span>
            </div>

            <!-- Tombol Toggle Sidebar -->
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-slate-800 text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-bars-staggered text-base"></i>
            </button>
        </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
                <!-- Dashboard Link -->
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('opd.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('*.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                    <i class="fa-solid fa-chart-pie text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                </a>

                <!-- MENU KHUSUS ADMIN -->
                @if(auth()->user()->role === 'admin')
                <div x-show="sidebarOpen" class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Menu Kelola Admin
                </div>

                <!-- Kelola Akun OPD -->
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-md' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                    <i class="fa-solid fa-building-user text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Akun OPD</span>
                </a>

                <!-- Kelola Tugas (Admin) -->
                <a href="{{ route('admin.tasks.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.tasks.*') ? 'bg-indigo-600 text-white shadow-md' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                    <i class="fa-solid fa-list-check text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Tugas</span>
                </a>

                <!-- Riwayat Login OPD (Baru) -->
                <a href="{{ route('admin.login-histories') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.login-histories') ? 'bg-indigo-600 text-white shadow-md' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                    <i class="fa-solid fa-clock-rotate-left text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Riwayat Login OPD</span>
                </a>
                @endif

                <!-- MENU KHUSUS OPD -->
                @if(auth()->user()->role === 'opd')
                <div x-show="sidebarOpen" class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Menu Pelaporan
                </div>

                <!-- Kelola Tugas / Pengumpulan Berkas (OPD) -->
                <a href="{{ route('opd.submissions.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('opd.submissions.*') ? 'bg-indigo-600 text-white shadow-md' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                    <i class="fa-solid fa-list-check text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Tugas</span>
                </a>
                @endif
            </nav>

            <!-- Profile Bottom Sidebar -->
            <div class="p-3 border-t border-slate-800 bg-slate-950/40">
                <div class="flex items-center gap-3 px-2 py-1">
                    <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-400 font-semibold flex items-center justify-center text-xs border border-indigo-500/30">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div x-show="sidebarOpen" class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-400 capitalize truncate">{{ Auth::user()->role }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- AREA UTAMA KONTEN -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Header Bar -->
            <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 sticky top-0 z-20 shadow-sm">
                <div>
                    @isset($header)
                    {{ $header }}
                    @endisset
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ Auth::user()->role === 'admin' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                        <i class="fa-solid fa-user-shield text-xs mr-1"></i>
                        {{ strtoupper(Auth::user()->role) }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors p-2" title="Keluar">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content Slot -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>