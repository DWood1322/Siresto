<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KokiController extends Controller
{
    // Sub-Menu 1: Kitchen Display System (KDS) - Layar Antrean Dapur
    public function index()
    {
        $pesananAktif = Pesanan::with(['details.menu', 'meja'])
            ->whereIn('status_pesanan', ['pending', 'dimasak'])
            ->orderBy('tgl_pesanan', 'asc')
            ->get();

        return view('koki.kds', compact('pesananAktif'));
    }

    // Sub-Menu 2: Kelola Ketersediaan Menu & Bahan Baku Dapur
    public function menuIndex()
    {
        $allMenu = Menu::orderBy('kategori', 'asc')->get();
        return view('koki.menu_management', compact('allMenu'));
    }

    // Toggle Status Ketersediaan Menu (tersedia <-> habis)
    public function toggleMenuStatus($kodeMenu)
    {
        $menu = Menu::findOrFail($kodeMenu);
        $newStatus = $menu->status_ketersediaan === 'tersedia' ? 'habis' : 'tersedia';

        $menu->update(['status_ketersediaan' => $newStatus]);

        $statusText = $newStatus === 'habis' ? 'HABIS (Bahan Baku Kosong)' : 'TERSEDIA (Bahan Baku Siap)';

        return redirect()->back()->with('success', 'Status ketersediaan menu "' . $menu->nama_menu . '" diubah menjadi: ' . $statusText);
    }

    // Koki Menambahkan Data Menu Baru
    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'kode_menu' => 'required|string|unique:menu,kode_menu',
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|in:Makanan,Minuman,Cemilan,Dessert',
            'harga' => 'required|numeric|min:0',
            'status_ketersediaan' => 'required|in:tersedia,habis',
        ]);

        Menu::create($validated);

        return redirect()->back()->with('success', 'Menu baru "' . $validated['nama_menu'] . '" berhasil ditambahkan!');
    }

    // 1-Click Complete Entire Order
    public function completeOrder($noPesanan)
    {
        return DB::transaction(function () use ($noPesanan) {
            $pesanan = Pesanan::findOrFail($noPesanan);

            DetailPesanan::where('no_pesanan', $noPesanan)->update(['status_item' => 'selesai']);
            $pesanan->update(['status_pesanan' => 'siap_disajikan']);

            return redirect()->back()->with('success', 'Pesanan Meja #' . $pesanan->no_meja . ' (' . $pesanan->no_pesanan . ') SELESAI dimasak & Siap Disajikan!');
        });
    }

    // Update Status Per-Item
    public function updateItemStatus(Request $request, $idDetail)
    {
        $validated = $request->validate([
            'status_item' => 'required|in:antri,dimasak,selesai',
        ]);

        $detail = DetailPesanan::findOrFail($idDetail);
        $detail->update(['status_item' => $validated['status_item']]);

        $pesanan = Pesanan::with('details')->findOrFail($detail->no_pesanan);
        $allDetails = $pesanan->details;

        if ($allDetails->every(fn($item) => $item->status_item === 'selesai')) {
            $pesanan->update(['status_pesanan' => 'siap_disajikan']);
        } elseif ($allDetails->contains(fn($item) => $item->status_item === 'dimasak')) {
            $pesanan->update(['status_pesanan' => 'dimasak']);
        }

        return redirect()->back()->with('success', 'Status item berhasil diperbarui!');
    }
}
