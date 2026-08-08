<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PelayanController extends Controller
{
    // Sub-Menu 1: Form Pemesanan POS Pelayan
    public function searchMeja(Request $request)
    {
        $status = $request->query('status', 'kosong');
        $meja = Meja::where('status_meja', $status)->get();
        $allMeja = Meja::all();
        // Ambil seluruh daftar menu (baik yang tersedia maupun yang habis untuk ditampilkan dengan badge stok habis)
        $menuList = Menu::orderBy('kategori', 'asc')->get();

        $pesananSiapDisajikan = Pesanan::with(['details.menu', 'meja'])
            ->where('status_pesanan', 'siap_disajikan')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('pelayan.order_form', compact('meja', 'allMeja', 'menuList', 'pesananSiapDisajikan'));
    }

    // Sub-Menu 2: Layar Khusus Notifikasi Masakan Siap Diantar ke Tamu
    public function readyOrders()
    {
        $pesananSiapDisajikan = Pesanan::with(['details.menu', 'meja'])
            ->where('status_pesanan', 'siap_disajikan')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('pelayan.ready_orders', compact('pesananSiapDisajikan'));
    }

    // Konfirmasi Makanan Telah Diantar ke Meja oleh Pelayan
    public function markServed($noPesanan)
    {
        $pesanan = Pesanan::findOrFail($noPesanan);
        $pesanan->update(['status_pesanan' => 'siap_disajikan']);

        return redirect()->back()->with('success', 'Masakan Meja #' . $pesanan->no_meja . ' telah dikonfirmasi diantar ke meja pelanggan!');
    }

    // Input Pesanan Atomic Transaction
    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'no_meja' => 'required|exists:meja,no_meja',
            'jumlah_tamu' => 'required|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.kode_menu' => 'required|exists:menu,kode_menu',
            'items.*.jumlah' => 'nullable|integer|min:0',
            'items.*.catatan' => 'nullable|string',
        ]);

        $selectedItems = array_filter($validated['items'], function ($item) {
            return isset($item['jumlah']) && intval($item['jumlah']) > 0;
        });

        if (empty($selectedItems)) {
            return redirect()->back()->withErrors(['items' => 'Silakan pilih minimal 1 item menu yang dipesan (kuantitas > 0).']);
        }

        // Verifikasi bahwa tidak ada item bernilai "habis" yang dipesan
        foreach ($selectedItems as $item) {
            $menu = Menu::findOrFail($item['kode_menu']);
            if ($menu->status_ketersediaan === 'habis') {
                return redirect()->back()->withErrors(['items' => 'Menu "' . $menu->nama_menu . '" sedang HABIS dan tidak dapat dipesan.']);
            }
        }

        return DB::transaction(function () use ($validated, $selectedItems) {
            $pelanggan = Pelanggan::create([
                'jumlah_tamu' => $validated['jumlah_tamu'],
                'no_meja' => $validated['no_meja'],
            ]);

            Meja::where('no_meja', $validated['no_meja'])->update(['status_meja' => 'terisi']);

            $noPesanan = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
            $pesanan = Pesanan::create([
                'no_pesanan' => $noPesanan,
                'tgl_pesanan' => now(),
                'no_meja' => $validated['no_meja'],
                'status_pesanan' => 'pending',
                'id_pelayan' => Auth::id() ?? 1,
                'id_pelanggan' => $pelanggan->id_pelanggan,
            ]);

            foreach ($selectedItems as $item) {
                $menu = Menu::findOrFail($item['kode_menu']);
                $jumlah = intval($item['jumlah']);
                $subtotal = $menu->harga * $jumlah;

                DetailPesanan::create([
                    'no_pesanan' => $pesanan->no_pesanan,
                    'kode_menu' => $item['kode_menu'],
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal,
                    'catatan' => $item['catatan'] ?? null,
                    'status_item' => 'antri',
                ]);
            }

            return redirect()->back()->with('success', 'Pesanan ' . $noPesanan . ' berhasil dikirim ke Dapur untuk Meja ' . $validated['no_meja']);
        });
    }
}
