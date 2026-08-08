<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Users
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama_user');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['Pelayan', 'Koki', 'Kasir', 'Pemilik Restoran']);
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Meja
        Schema::create('meja', function (Blueprint $table) {
            $table->string('no_meja')->primary();
            $table->integer('kapasitas_kursi');
            $table->enum('status_meja', ['kosong', 'terisi', 'reservasi'])->default('kosong');
            $table->timestamps();
        });

        // 3. Menu
        Schema::create('menu', function (Blueprint $table) {
            $table->string('kode_menu')->primary();
            $table->string('nama_menu');
            $table->enum('kategori', ['Makanan', 'Minuman', 'Cemilan', 'Dessert']);
            $table->decimal('harga', 12, 2);
            $table->enum('status_ketersediaan', ['tersedia', 'habis'])->default('tersedia');
            $table->timestamps();
        });

        // 4. Pelanggan
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->integer('jumlah_tamu');
            $table->string('no_meja');
            $table->foreign('no_meja')->references('no_meja')->on('meja')->onDelete('cascade');
            $table->timestamps();
        });

        // 5. Pesanan
        Schema::create('pesanan', function (Blueprint $table) {
            $table->string('no_pesanan')->primary();
            $table->dateTime('tgl_pesanan');
            $table->string('no_meja');
            $table->enum('status_pesanan', ['pending', 'dimasak', 'siap_disajikan', 'selesai', 'dibatalkan'])->default('pending');
            $table->unsignedBigInteger('id_pelayan');
            $table->unsignedBigInteger('id_pelanggan');

            $table->foreign('no_meja')->references('no_meja')->on('meja');
            $table->foreign('id_pelayan')->references('id_user')->on('users');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan');
            $table->timestamps();
        });

        // 6. Detail Pesanan
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('no_pesanan');
            $table->string('kode_menu');
            $table->integer('jumlah');
            $table->decimal('subtotal', 12, 2);
            $table->text('catatan')->nullable();
            $table->enum('status_item', ['antri', 'dimasak', 'selesai'])->default('antri');

            $table->foreign('no_pesanan')->references('no_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('kode_menu')->references('kode_menu')->on('menu');
            $table->timestamps();
        });

        // 7. Pembayaran
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->string('no_transaksi')->primary();
            $table->string('no_pesanan');
            $table->dateTime('tgl_bayar');
            $table->decimal('total_tagihan', 12, 2);
            $table->enum('metode_bayar', ['tunai', 'qris', 'debit', 'kredit']);
            $table->decimal('jumlah_bayar', 12, 2);
            $table->decimal('kembalian', 12, 2);
            $table->enum('status_pembayaran', ['lunas', 'pending', 'gagal'])->default('lunas');
            $table->unsignedBigInteger('id_kasir');

            $table->foreign('no_pesanan')->references('no_pesanan')->on('pesanan');
            $table->foreign('id_kasir')->references('id_user')->on('users');
            $table->timestamps();
        });

        // 8. Laporan Pendapatan
        Schema::create('laporan_pendapatan', function (Blueprint $table) {
            $table->string('no_laporan')->primary();
            $table->enum('periode_laporan', ['harian', 'mingguan', 'bulanan']);
            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->decimal('total_pendapatan', 14, 2);
            $table->dateTime('tgl_cetak');
            $table->unsignedBigInteger('id_kasir');
            $table->enum('status_validasi', ['draft', 'tervalidasi'])->default('draft');

            $table->foreign('id_kasir')->references('id_user')->on('users');
            $table->timestamps();
        });

        // 9. Sessions Table (Laravel HTTP Session)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('laporan_pendapatan');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('meja');
        Schema::dropIfExists('users');
    }
};
