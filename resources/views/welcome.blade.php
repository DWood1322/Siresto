@extends('layouts.app')

@section('title', 'SIResto — Home Navigasi Role')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 py-8">
    <div class="text-center space-y-3">
        <h1 class="text-4xl font-extrabold text-slate-900">Selamat Datang di SIResto</h1>
        <p class="text-slate-600 max-w-xl mx-auto">Sistem Informasi Management Restaurant Berbasis Laravel 11 & Tailwind CSS dengan Arsitektur Data Ground Truth ERD.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="/pelayan/order" class="p-6 bg-white rounded-xl shadow-sm border border-slate-200 hover:border-amber-500 hover:shadow-md transition space-y-2 group">
            <div class="flex justify-between items-center">
                <span class="text-2xl">📋</span>
                <span class="text-xs bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded">ROLE: PELAYAN</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 group-hover:text-amber-600">Dashboard & Form Order Pelayan</h2>
            <p class="text-sm text-slate-500">Pencarian meja kosong, pendaftaran jumlah tamu, dan input pesanan bertahap (Atomic Transaction).</p>
        </a>

        <a href="/koki/kds" class="p-6 bg-white rounded-xl shadow-sm border border-slate-200 hover:border-red-500 hover:shadow-md transition space-y-2 group">
            <div class="flex justify-between items-center">
                <span class="text-2xl">🔥</span>
                <span class="text-xs bg-red-100 text-red-800 font-bold px-2 py-0.5 rounded">ROLE: KOKI</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 group-hover:text-red-600">Kitchen Display System (KDS)</h2>
            <p class="text-sm text-slate-500">Antrean pesanan dapur realtime, update status item (antri ➔ dimasak ➔ selesai), dan sinkronisasi otomatis.</p>
        </a>

        <a href="/kasir/kasir" class="p-6 bg-white rounded-xl shadow-sm border border-slate-200 hover:border-blue-500 hover:shadow-md transition space-y-2 group">
            <div class="flex justify-between items-center">
                <span class="text-2xl">💳</span>
                <span class="text-xs bg-blue-100 text-blue-800 font-bold px-2 py-0.5 rounded">ROLE: KASIR</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 group-hover:text-blue-600">Kasir & Pelunasan Pembayaran</h2>
            <p class="text-sm text-slate-500">Proses transaksi tagihan, hitung kembalian, cetak bukti pembayaran, dan otomatis pengosongan meja.</p>
        </a>

        <a href="/pemilik/dashboard" class="p-6 bg-white rounded-xl shadow-sm border border-slate-200 hover:border-emerald-500 hover:shadow-md transition space-y-2 group">
            <div class="flex justify-between items-center">
                <span class="text-2xl">📊</span>
                <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded">ROLE: PEMILIK RESTORAN</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 group-hover:text-emerald-600">Dashboard Eksekutif & Laporan</h2>
            <p class="text-sm text-slate-500">Monitoring omzet pendapatan harian/bulanan, performa transaksi, dan rekapitulasi data laporan.</p>
        </a>
    </div>
</div>
@endsection
