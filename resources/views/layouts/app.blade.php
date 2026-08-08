<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIResto POS System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex flex-col">

    <!-- Global Navigation Header (Role Aware) -->
    <header class="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16 border-b border-slate-800">
            <!-- Brand -->
            <div class="flex items-center space-x-3">
                <a href="/" class="text-xl font-black tracking-wider text-emerald-400 flex items-center space-x-2">
                    <span>🍽️ SIResto POS</span>
                </a>
            </div>

            <!-- Main Role Selector -->
            @auth
                <nav class="hidden md:flex items-center space-x-2 text-xs font-semibold">
                    @if(Auth::user()->role === 'Pelayan' || Auth::user()->role === 'Pemilik Restoran')
                        <a href="{{ route('pelayan.order.form') }}"
                            class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('pelayan.*') ? 'bg-amber-500 text-slate-950 font-black shadow-md' : 'text-slate-300 hover:bg-slate-800' }}">
                            📋 Pelayan POS
                        </a>
                    @endif

                    @if(Auth::user()->role === 'Koki' || Auth::user()->role === 'Pemilik Restoran')
                        <a href="{{ route('koki.kds') }}"
                            class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('koki.*') ? 'bg-red-600 text-white font-black shadow-md' : 'text-slate-300 hover:bg-slate-800' }}">
                            🔥 Dapur Koki
                        </a>
                    @endif

                    @if(Auth::user()->role === 'Kasir' || Auth::user()->role === 'Pemilik Restoran')
                        <a href="{{ route('kasir.index') }}"
                            class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('kasir.*') ? 'bg-blue-600 text-white font-black shadow-md' : 'text-slate-300 hover:bg-slate-800' }}">
                            💳 Kasir POS
                        </a>
                    @endif

                    @if(Auth::user()->role === 'Pemilik Restoran')
                        <a href="{{ route('pemilik.dashboard') }}"
                            class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('pemilik.*') ? 'bg-emerald-600 text-white font-black shadow-md' : 'text-slate-300 hover:bg-slate-800' }}">
                            📊 Dashboard Bos
                        </a>
                    @endif
                </nav>

                <!-- User & Logout -->
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-100">{{ Auth::user()->nama_user }}</p>
                        <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded font-bold
                            {{ Auth::user()->role === 'Pemilik Restoran' ? 'bg-emerald-500 text-slate-950' : '' }}
                            {{ Auth::user()->role === 'Pelayan' ? 'bg-amber-500 text-slate-950' : '' }}
                            {{ Auth::user()->role === 'Koki' ? 'bg-red-600 text-white' : '' }}
                            {{ Auth::user()->role === 'Kasir' ? 'bg-blue-600 text-white' : '' }}">
                            {{ Auth::user()->role }}
                        </span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition">
                            🚪 <span class="hidden sm:inline text-xs ml-1">Keluar</span>
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        <!-- Sub-Navigation Tabs Bar per Role -->
        @auth
            <div class="bg-slate-950 px-4 sm:px-6 lg:px-8 py-2">
                <div class="max-w-7xl mx-auto flex items-center space-x-3 text-xs">

                    <!-- Sub-Tabs Koki -->
                    @if(request()->routeIs('koki.*'))
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Sub-Proses Koki:</span>
                        <a href="{{ route('koki.kds') }}"
                            class="px-3 py-1 rounded-lg transition {{ request()->routeIs('koki.kds') ? 'bg-red-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            🔥 1. Layar Antrean Dapur (KDS)
                        </a>
                        <a href="{{ route('koki.menu') }}"
                            class="px-3 py-1 rounded-lg transition {{ request()->routeIs('koki.menu*') ? 'bg-red-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            📦 2. Kelola Stok Menu & Bahan Baku
                        </a>
                    @endif

                    <!-- Sub-Tabs Pelayan -->
                    @if(request()->routeIs('pelayan.*'))
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Sub-Proses Pelayan:</span>
                        <a href="{{ route('pelayan.order.form') }}"
                            class="px-3 py-1 rounded-lg transition {{ request()->routeIs('pelayan.order.*') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            📋 1. Form Pemesanan POS
                        </a>
                        <a href="{{ route('pelayan.ready') }}"
                            class="px-3 py-1 rounded-lg transition {{ request()->routeIs('pelayan.ready') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            🔔 2. Notifikasi Siap Diantar
                        </a>
                    @endif

                    <!-- Sub-Tabs Kasir -->
                    @if(request()->routeIs('kasir.*'))
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Sub-Proses Kasir:</span>
                        <a href="{{ route('kasir.index') }}"
                            class="px-3 py-1 rounded-lg transition {{ request()->routeIs('kasir.index') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            💳 1. Checkout POS Pelunasan
                        </a>
                        <a href="{{ route('kasir.history') }}"
                            class="px-3 py-1 rounded-lg transition {{ request()->routeIs('kasir.history') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            📜 2. Riwayat Transaksi & Log
                        </a>
                    @endif

                    <!-- Sub-Tabs Pemilik -->
                    @if(request()->routeIs('pemilik.*'))
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Sub-Proses Bos:</span>
                        <a href="{{ route('pemilik.dashboard') }}"
                            class="px-3 py-1 rounded-lg transition bg-emerald-600 text-white font-bold">
                            📊 Executive Overview & Validasi Laporan (D5)
                        </a>
                    @endif

                </div>
            </div>
        @endauth
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-900 rounded-lg shadow-sm text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-900 rounded-lg shadow-sm text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-3 text-center text-xs text-slate-500">
        SIResto POS System &copy; {{ date('Y') }} — Hak Akses & Sub-Proses Role Terstruktur
    </footer>

</body>
</html>
