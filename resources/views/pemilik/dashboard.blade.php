@extends('layouts.app')

@section('title', 'Dashboard Executive Bos — SIResto')

@section('content')
<div class="space-y-6">
    <!-- Header Dashboard Bos -->
    <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="bg-emerald-500 text-slate-900 text-xs font-black px-2.5 py-1 rounded-full uppercase">VIP BOS / PEMILIK RESTORAN</span>
                <h1 class="text-2xl font-black tracking-tight">Dashboard Executive SIResto</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Pemantauan omzet realtime, performa operasional, serta akses penuh ke seluruh modul sistem.</p>
        </div>
        <div class="text-xs font-mono text-emerald-400 bg-slate-800 px-4 py-2 rounded-xl border border-slate-700">
            Status: System Active 🟢
        </div>
    </div>

    <!-- Quick Navigation Access for Bos to all Roles -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 space-y-3">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Pintasan Akses Cepat Semua Modul System (Akses Khusus Bos)</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <a href="{{ route('pelayan.order.form') }}" class="p-3 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition flex items-center justify-between text-amber-900">
                <span class="font-bold text-xs">📋 Modul Pelayan (Order)</span>
                <span class="text-xs">➔</span>
            </a>
            <a href="{{ route('koki.kds') }}" class="p-3 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition flex items-center justify-between text-red-900">
                <span class="font-bold text-xs">🔥 Modul Dapur (KDS Koki)</span>
                <span class="text-xs">➔</span>
            </a>
            <a href="{{ route('kasir.index') }}" class="p-3 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 transition flex items-center justify-between text-blue-900">
                <span class="font-bold text-xs">💳 Modul Kasir (POS Pelunasan)</span>
                <span class="text-xs">➔</span>
            </a>
        </div>
    </div>

    <!-- Executive Metric Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan Hari Ini</p>
            <p class="text-3xl font-black font-mono text-emerald-600 mt-2">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Realtime omzet dari transaksi `pembayaran` lunas</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pesanan Diproses</p>
            <p class="text-3xl font-black text-blue-600 mt-2">{{ $totalPesananHariIni }} <span class="text-base font-normal text-slate-500">Pesanan</span></p>
            <p class="text-[11px] text-slate-500 mt-1">Total akumulasi order dari tabel `pesanan`</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Menu Aktif Restoran</p>
            <p class="text-3xl font-black text-amber-600 mt-2">{{ $totalMenuAktif }} <span class="text-base font-normal text-slate-500">Item</span></p>
            <p class="text-[11px] text-slate-500 mt-1">Status ketersediaan dari tabel `menu`</p>
        </div>
    </div>

    <!-- Generate Laporan Action & Laporan Table (D5 Data Store) -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-4 gap-4">
            <div>
                <h2 class="font-bold text-slate-900 text-lg">📊 Validasi Laporan Pendapatan (Data Store D5)</h2>
                <p class="text-xs text-slate-500">Rekapitulasi omzet pendapatan resmi restoran yang telah ter-generate</p>
            </div>

            <!-- Form Generate Laporan Realtime -->
            <form action="{{ route('pemilik.laporan.generate') }}" method="POST" class="flex items-center space-x-2">
                @csrf
                <select name="periode_laporan" required class="border border-slate-300 rounded-xl p-2 text-xs font-semibold bg-slate-50">
                    <option value="harian">Periode Harian</option>
                    <option value="mingguan">Periode Mingguan</option>
                    <option value="bulanan">Periode Bulanan</option>
                </select>

                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm">
                    ⚡ Generate Laporan
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="p-3">No. Laporan</th>
                        <th class="p-3">Periode</th>
                        <th class="p-3">Rentang Tanggal</th>
                        <th class="p-3">Total Pendapatan</th>
                        <th class="p-3">Tgl Cetak</th>
                        <th class="p-3">Status Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporanList as $lap)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 font-mono font-bold text-slate-900">{{ $lap->no_laporan }}</td>
                            <td class="p-3 capitalize font-semibold">{{ $lap->periode_laporan }}</td>
                            <td class="p-3">{{ $lap->tgl_awal }} s/d {{ $lap->tgl_akhir }}</td>
                            <td class="p-3 font-mono font-extrabold text-emerald-600">Rp {{ number_format($lap->total_pendapatan, 0, ',', '.') }}</td>
                            <td class="p-3">{{ $lap->tgl_cetak }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase
                                    {{ $lap->status_validasi === 'tervalidasi' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $lap->status_validasi }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 italic">Belum ada data laporan pendapatan ter-generate saat ini. Klik tombol "Generate Laporan" di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
