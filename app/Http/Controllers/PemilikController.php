<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Menu;
use App\Models\LaporanPendapatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PemilikController extends Controller
{
    public function dashboard()
    {
        $totalPendapatanHariIni = Pembayaran::whereDate('tgl_bayar', now()->toDateString())->sum('total_tagihan');
        $totalPesananHariIni = Pesanan::whereDate('tgl_pesanan', now()->toDateString())->count();
        $totalMenuAktif = Menu::where('status_ketersediaan', 'tersedia')->count();

        $laporanList = LaporanPendapatan::latest()->take(10)->get();

        return view('pemilik.dashboard', compact(
            'totalPendapatanHariIni',
            'totalPesananHariIni',
            'totalMenuAktif',
            'laporanList'
        ));
    }

    // Generate Laporan Pendapatan Baru (D5) secara Realtime dari data Pembayaran
    public function generateLaporan(Request $request)
    {
        $validated = $request->validate([
            'periode_laporan' => 'required|in:harian,mingguan,bulanan',
        ]);

        $periode = $validated['periode_laporan'];
        $tglAwal = now()->startOfDay()->toDateString();
        $tglAkhir = now()->endOfDay()->toDateString();

        if ($periode === 'mingguan') {
            $tglAwal = now()->startOfWeek()->toDateString();
        } elseif ($periode === 'bulanan') {
            $tglAwal = now()->startOfMonth()->toDateString();
        }

        $totalPendapatan = Pembayaran::whereBetween('tgl_bayar', [$tglAwal . ' 00:00:00', $tglAkhir . ' 23:59:59'])
            ->where('status_pembayaran', 'lunas')
            ->sum('total_tagihan');

        $noLaporan = 'LAP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        LaporanPendapatan::create([
            'no_laporan' => $noLaporan,
            'periode_laporan' => $periode,
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'total_pendapatan' => $totalPendapatan,
            'tgl_cetak' => now(),
            'id_kasir' => Auth::id() ?? 4,
            'status_validasi' => 'tervalidasi',
        ]);

        return redirect()->back()->with('success', 'Laporan Pendapatan ' . $periode . ' (' . $noLaporan . ') Berhasil Di-generate & Divalidasi!');
    }
}
