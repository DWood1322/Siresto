<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\LaporanPendapatan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users for each role
        $pelayan = User::create([
            'nama_user' => 'Budi Pelayan',
            'username' => 'pelayan1',
            'password' => Hash::make('password'),
            'role' => 'Pelayan',
        ]);

        $koki = User::create([
            'nama_user' => 'Chef Juna',
            'username' => 'koki1',
            'password' => Hash::make('password'),
            'role' => 'Koki',
        ]);

        $kasir = User::create([
            'nama_user' => 'Siti Kasir',
            'username' => 'kasir1',
            'password' => Hash::make('password'),
            'role' => 'Kasir',
        ]);

        $pemilik = User::create([
            'nama_user' => 'Pak Manager',
            'username' => 'pemilik1',
            'password' => Hash::make('password'),
            'role' => 'Pemilik Restoran',
        ]);

        // 2. Seed Meja
        $mejaData = [
            ['no_meja' => 'M-01', 'kapasitas_kursi' => 2, 'status_meja' => 'kosong'],
            ['no_meja' => 'M-02', 'kapasitas_kursi' => 4, 'status_meja' => 'kosong'],
            ['no_meja' => 'M-03', 'kapasitas_kursi' => 4, 'status_meja' => 'kosong'],
            ['no_meja' => 'M-04', 'kapasitas_kursi' => 6, 'status_meja' => 'kosong'],
            ['no_meja' => 'M-05', 'kapasitas_kursi' => 8, 'status_meja' => 'kosong'],
        ];

        foreach ($mejaData as $m) {
            Meja::create($m);
        }

        // 3. Seed Menu
        $menuData = [
            ['kode_menu' => 'MN-001', 'nama_menu' => 'Nasi Goreng Spesial SIResto', 'kategori' => 'Makanan', 'harga' => 35000, 'status_ketersediaan' => 'tersedia'],
            ['kode_menu' => 'MN-002', 'nama_menu' => 'Ayam Bakar Madu', 'kategori' => 'Makanan', 'harga' => 42000, 'status_ketersediaan' => 'tersedia'],
            ['kode_menu' => 'MN-003', 'nama_menu' => 'Mie Goreng Seafood', 'kategori' => 'Makanan', 'harga' => 38000, 'status_ketersediaan' => 'tersedia'],
            ['kode_menu' => 'MN-004', 'nama_menu' => 'Es Teh Manis Jumbo', 'kategori' => 'Minuman', 'harga' => 10000, 'status_ketersediaan' => 'tersedia'],
            ['kode_menu' => 'MN-005', 'nama_menu' => 'Jus Alpukat Kocok', 'kategori' => 'Minuman', 'harga' => 20000, 'status_ketersediaan' => 'tersedia'],
            ['kode_menu' => 'MN-006', 'nama_menu' => 'French Fries & Dip', 'kategori' => 'Cemilan', 'harga' => 22000, 'status_ketersediaan' => 'tersedia'],
            ['kode_menu' => 'MN-007', 'nama_menu' => 'Pancake Ice Cream Vanilla', 'kategori' => 'Dessert', 'harga' => 28000, 'status_ketersediaan' => 'tersedia'],
        ];

        foreach ($menuData as $mn) {
            Menu::create($mn);
        }

        // 4. Seed Sample Completed Transactions & Laporan Pendapatan (D5)
        $pelanggan1 = Pelanggan::create(['jumlah_tamu' => 2, 'no_meja' => 'M-01']);
        $pesanan1 = Pesanan::create([
            'no_pesanan' => 'ORD-' . date('Ymd') . '-SAMPLE1',
            'tgl_pesanan' => now()->subHours(2),
            'no_meja' => 'M-01',
            'status_pesanan' => 'selesai',
            'id_pelayan' => $pelayan->id_user,
            'id_pelanggan' => $pelanggan1->id_pelanggan,
        ]);

        DetailPesanan::create([
            'no_pesanan' => $pesanan1->no_pesanan,
            'kode_menu' => 'MN-001',
            'jumlah' => 2,
            'subtotal' => 70000,
            'catatan' => 'Pedas sedang',
            'status_item' => 'selesai',
        ]);

        DetailPesanan::create([
            'no_pesanan' => $pesanan1->no_pesanan,
            'kode_menu' => 'MN-004',
            'jumlah' => 2,
            'subtotal' => 20000,
            'catatan' => 'Es sedikit',
            'status_item' => 'selesai',
        ]);

        Pembayaran::create([
            'no_transaksi' => 'TRX-' . date('Ymd') . '-SAMPLE1',
            'no_pesanan' => $pesanan1->no_pesanan,
            'tgl_bayar' => now()->subHours(1),
            'total_tagihan' => 90000,
            'metode_bayar' => 'qris',
            'jumlah_bayar' => 90000,
            'kembalian' => 0,
            'status_pembayaran' => 'lunas',
            'id_kasir' => $kasir->id_user,
        ]);

        // Seed Sample Laporan Pendapatan (D5)
        LaporanPendapatan::create([
            'no_laporan' => 'LAP-' . date('Ym') . '-001',
            'periode_laporan' => 'harian',
            'tgl_awal' => now()->toDateString(),
            'tgl_akhir' => now()->toDateString(),
            'total_pendapatan' => 90000,
            'tgl_cetak' => now(),
            'id_kasir' => $kasir->id_user,
            'status_validasi' => 'tervalidasi',
        ]);
    }
}
