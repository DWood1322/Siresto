@extends('layouts.app')

@section('title', 'Notifikasi Masakan Siap Diantar — Pelayan SIResto')

@section('content')
<div class="space-y-6">
    <!-- Header Sub-Menu Pelayan -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Pelayan Notification</span>
                <h1 class="text-xl font-bold text-slate-900">Daftar Masakan Siap Diantar ke Tamu</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Daftar pesanan yang telah selesai dimasak oleh Koki di dapur dan menunggu diantar oleh Pelayan.</p>
        </div>
        <a href="{{ route('pelayan.order.form') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl transition">
            📋 Kembali ke Form Pemesanan POS
        </a>
    </div>

    <!-- Active Ready Orders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($pesananSiapDisajikan as $readyOrder)
            <div class="bg-white text-slate-800 p-5 rounded-2xl shadow-sm border-2 border-emerald-400 flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xl font-black text-slate-900">MEJA #{{ $readyOrder->no_meja }}</span>
                        <span class="text-xs bg-emerald-100 text-emerald-800 font-extrabold px-2.5 py-1 rounded-full">SIAP SAJI</span>
                    </div>
                    <p class="text-xs text-slate-400 font-mono mb-3">{{ $readyOrder->no_pesanan }}</p>

                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b pb-1 mb-2">Item Masakan:</p>
                    <ul class="text-xs space-y-2 text-slate-700">
                        @foreach($readyOrder->details as $item)
                            <li class="flex justify-between items-center bg-slate-50 p-2 rounded-lg border border-slate-100">
                                <span class="font-bold text-slate-900">{{ $item->jumlah }}x {{ $item->menu->nama_menu }}</span>
                                <span class="text-emerald-600 font-semibold text-[11px]">✓ Selesai</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <form action="{{ route('pelayan.order.serve', $readyOrder->no_pesanan) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold py-3 px-4 rounded-xl transition flex items-center justify-center space-x-1 shadow">
                        <span>🍽️ Konfirmasi Makanan Telah Diantar ke Meja</span>
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-full bg-white p-16 text-center text-slate-400 rounded-2xl border border-dashed border-slate-300 space-y-2">
                <p class="font-bold text-base text-slate-700">Tidak ada masakan yang menunggu diantar saat ini.</p>
                <p class="text-xs text-slate-400">Begitu Koki menyelesaikan pesanan, notifikasi akan otomatis muncul di halaman ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
