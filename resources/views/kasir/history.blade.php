@extends('layouts.app')

@section('title', 'Riwayat Transaksi Kasir — SIResto')

@section('content')
<div class="space-y-6">
    <!-- Header Sub-Menu Kasir -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Kasir History</span>
                <h1 class="text-xl font-bold text-slate-900">Riwayat Transaksi & Log Pembayaran</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Seluruh log transaksi pelunasan pembayaran yang telah berhasil diproses oleh Kasir.</p>
        </div>
        <a href="{{ route('kasir.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
            💳 Kembali ke Layar Kasir POS
        </a>
    </div>

    <!-- History Table -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="p-3">No. Transaksi</th>
                        <th class="p-3">No. Pesanan</th>
                        <th class="p-3">Waktu Bayar</th>
                        <th class="p-3">Metode</th>
                        <th class="p-3">Total Tagihan</th>
                        <th class="p-3">Dibayar</th>
                        <th class="p-3">Kembalian</th>
                        <th class="p-3">Kasir</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($allPembayaran as $trx)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 font-mono font-bold text-slate-900">{{ $trx->no_transaksi }}</td>
                            <td class="p-3 font-mono text-slate-500">{{ $trx->no_pesanan }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($trx->tgl_bayar)->format('d-M-Y H:i') }}</td>
                            <td class="p-3 font-bold uppercase text-blue-600">{{ $trx->metode_bayar }}</td>
                            <td class="p-3 font-mono font-black text-emerald-600 text-sm">Rp {{ number_format($trx->total_tagihan, 0, ',', '.') }}</td>
                            <td class="p-3 font-mono">Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="p-3 font-mono">Rp {{ number_format($trx->kembalian, 0, ',', '.') }}</td>
                            <td class="p-3">{{ $trx->kasir->nama_user ?? 'Kasir' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase bg-emerald-100 text-emerald-800">
                                    {{ $trx->status_pembayaran }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400 italic">Belum ada riwayat transaksi pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $allPembayaran->links() }}
        </div>
    </div>
</div>
@endsection
