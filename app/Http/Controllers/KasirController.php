<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    // Sub-Menu 1: Halaman Kasir POS Checkout Pelunasan
    public function index()
    {
        $pesananSiapBayar = Pesanan::with(['details.menu', 'pelanggan', 'meja'])
            ->whereIn('status_pesanan', ['siap_disajikan', 'dimasak', 'pending'])
            ->orderBy('tgl_pesanan', 'asc')
            ->get();

        $transaksiHariIni = Pembayaran::with(['pesanan', 'kasir'])
            ->whereDate('tgl_bayar', now()->toDateString())
            ->latest()
            ->get();

        return view('kasir.kasir', compact('pesananSiapBayar', 'transaksiHariIni'));
    }

    // Sub-Menu 2: Riwayat Seluruh Transaksi & Log Cetak Struk Kasir
    public function historyIndex()
    {
        $allPembayaran = Pembayaran::with(['pesanan.details.menu', 'kasir'])
            ->latest()
            ->paginate(15);

        return view('kasir.history', compact('allPembayaran'));
    }

    // Pelunasan Pembayaran & Release Meja
    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'no_pesanan' => 'required|exists:pesanan,no_pesanan',
            'metode_bayar' => 'required|in:tunai,qris,debit,kredit',
            'jumlah_bayar' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {
            $pesanan = Pesanan::with('details')->findOrFail($validated['no_pesanan']);
            $totalTagihan = $pesanan->details->sum('subtotal');

            if ($validated['jumlah_bayar'] < $totalTagihan) {
                return redirect()->back()->withErrors(['jumlah_bayar' => 'Jumlah bayar kurang dari total tagihan!']);
            }

            $kembalian = $validated['jumlah_bayar'] - $totalTagihan;
            $noTransaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            // 1. Simpan Pembayaran
            Pembayaran::create([
                'no_transaksi' => $noTransaksi,
                'no_pesanan' => $pesanan->no_pesanan,
                'tgl_bayar' => now(),
                'total_tagihan' => $totalTagihan,
                'metode_bayar' => $validated['metode_bayar'],
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'kembalian' => $kembalian,
                'status_pembayaran' => 'lunas',
                'id_kasir' => Auth::id() ?? 3,
            ]);

            // 2. Update Status Pesanan
            $pesanan->update(['status_pesanan' => 'selesai']);

            // 3. Kosongkan Meja
            Meja::where('no_meja', $pesanan->no_meja)->update(['status_meja' => 'kosong']);

            return redirect()->back()->with('success', 'Pembayaran ' . $noTransaksi . ' Berhasil Dilunasi! Kembalian: Rp ' . number_format($kembalian, 0, ',', '.'));
        });
    }
}
