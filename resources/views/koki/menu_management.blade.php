@extends('layouts.app')

@section('title', 'Manajemen Stok Menu & Bahan Baku — Koki SIResto')

@section('content')
<div class="space-y-6">
    <!-- Header Sub-Menu Koki -->
    <div class="bg-slate-900 text-white p-5 rounded-2xl shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="bg-red-600 text-white text-xs font-black px-2.5 py-1 rounded-full uppercase">KOKI DAPUR</span>
                <h1 class="text-xl font-bold">Manajemen Stok Menu & Bahan Baku</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Perbarui status ketersediaan menu berdasarkan ketersediaan bahan baku dapur secara realtime.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('koki.kds') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-xl border border-slate-700 transition">
                🔥 Layar Antrean Dapur (KDS) ➔
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- List Menu Cards (2 Cols) -->
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($allMenu as $menu)
                <div class="p-4 rounded-xl border transition flex flex-col justify-between space-y-3
                    {{ $menu->status_ketersediaan === 'tersedia' ? 'bg-white border-slate-200 shadow-sm' : 'bg-red-50 border-red-200 shadow-sm' }}">
                    <div>
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                                {{ $menu->kategori }}
                            </span>
                            <span class="text-xs font-mono font-bold text-slate-400">#{{ $menu->kode_menu }}</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base leading-snug">{{ $menu->nama_menu }}</h3>
                        <p class="text-xs font-mono font-bold text-emerald-600 mt-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-extrabold px-2.5 py-1 rounded-full
                            {{ $menu->status_ketersediaan === 'tersedia' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-600 text-white' }}">
                            {{ $menu->status_ketersediaan === 'tersedia' ? '🟢 Tersedia' : '🔴 Habis (Bahan Kosong)' }}
                        </span>

                        <form action="{{ route('koki.menu.toggle', $menu->kode_menu) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg border transition
                                {{ $menu->status_ketersediaan === 'tersedia' ? 'bg-red-100 text-red-700 hover:bg-red-200 border-red-300' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 border-emerald-300' }}">
                                {{ $menu->status_ketersediaan === 'tersedia' ? 'Set Habis ❌' : 'Set Tersedia ✅' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Form Tambah Menu Baru oleh Koki (1 Col) -->
        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-4 h-fit sticky top-20 shadow-sm">
            <h3 class="font-bold text-slate-900 text-sm border-b border-slate-200 pb-2">➕ Tambah Menu Baru Dapur</h3>

            <form action="{{ route('koki.menu.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Kode Menu (Unik)</label>
                    <input type="text" name="kode_menu" placeholder="misal: MN-008" required class="w-full text-xs p-2.5 border rounded-lg bg-white">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama Menu</label>
                    <input type="text" name="nama_menu" placeholder="Nama Masakan" required class="w-full text-xs p-2.5 border rounded-lg bg-white">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" required class="w-full text-xs p-2.5 border rounded-lg bg-white">
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Cemilan">Cemilan</option>
                        <option value="Dessert">Dessert</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" min="0" placeholder="30000" required class="w-full text-xs p-2.5 border rounded-lg bg-white font-mono">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Status Ketersediaan Awal</label>
                    <select name="status_ketersediaan" required class="w-full text-xs p-2.5 border rounded-lg bg-white">
                        <option value="tersedia">Tersedia (Bahan Baku Siap)</option>
                        <option value="habis">Habis (Bahan Baku Kosong)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-2.5 rounded-lg transition shadow-sm">
                    Simpan Menu Baru
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
