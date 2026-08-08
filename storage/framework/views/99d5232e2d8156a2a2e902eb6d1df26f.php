<?php $__env->startSection('title', 'Kasir POS & Pelunasan — SIResto'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header Kasir POS -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Kasir POS</span>
                <h1 class="text-xl font-bold text-slate-900">Pelunasan Tagihan & Transaksi Pembayaran</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Role: Kasir | Proses pelunasan pembayaran pelanggan dan otomatis pengosongan status meja.</p>
        </div>
        <span class="bg-blue-600 text-white font-bold text-xs px-3 py-1.5 rounded-xl shadow-sm">
            <?php echo e($pesananSiapBayar->count()); ?> Tagihan Menunggu
        </span>
    </div>

    <!-- Main Kasir Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Active Unpaid Orders Grid & Receipt Detail -->
        <div class="lg:col-span-2 space-y-6">
            <h2 class="font-bold text-slate-900 text-base flex items-center justify-between">
                <span>📋 Daftar Meja Menunggu Pembayaran</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php $__empty_1 = true; $__currentLoopData = $pesananSiapBayar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $totalTagihan = $order->details->sum('subtotal');
                    ?>
                    <div class="order-card bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-500 transition cursor-pointer flex flex-col justify-between"
                        onclick="selectOrder('<?php echo e($order->no_pesanan); ?>', '<?php echo e($order->no_meja); ?>', '<?php echo e($totalTagihan); ?>')">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-lg font-black text-slate-900">Meja #<?php echo e($order->no_meja); ?></span>
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded
                                    <?php echo e($order->status_pesanan === 'siap_disajikan' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'); ?>">
                                    <?php echo e($order->status_pesanan); ?>

                                </span>
                            </div>
                            <p class="text-xs text-slate-400 font-mono mb-2"><?php echo e($order->no_pesanan); ?></p>
                            <p class="text-xs text-slate-600 mb-3">Tamu: <?php echo e($order->pelanggan->jumlah_tamu ?? 1); ?> org | Items: <?php echo e($order->details->count()); ?> jenis</p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-xs text-slate-500 font-medium">Total Tagihan</span>
                            <span class="font-black font-mono text-blue-600 text-lg">Rp <?php echo e(number_format($totalTagihan, 0, ',', '.')); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full bg-white p-12 text-center text-slate-400 rounded-2xl border border-dashed border-slate-300">
                        <p class="font-medium text-sm">Tidak ada tagihan aktif yang belum dibayar saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Riwayat Transaksi Hari Ini -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                <h2 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3">🕒 Riwayat Pelunasan Transaksi Hari Ini</h2>
                <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                    <?php $__empty_1 = true; $__currentLoopData = $transaksiHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs">
                            <div>
                                <span class="font-bold text-slate-900 font-mono"><?php echo e($trx->no_transaksi); ?></span>
                                <p class="text-slate-500 text-[11px]">No Pesanan: <?php echo e($trx->no_pesanan); ?> | Metode: <?php echo e(strtoupper($trx->metode_bayar)); ?></p>
                            </div>
                            <div class="text-right">
                                <span class="font-bold font-mono text-emerald-600 text-sm">Rp <?php echo e(number_format($trx->total_tagihan, 0, ',', '.')); ?></span>
                                <p class="text-[10px] text-slate-400">Kembalian: Rp <?php echo e(number_format($trx->kembalian, 0, ',', '.')); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-xs text-slate-400 text-center py-4 italic">Belum ada transaksi pelunasan hari ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Kasir Checkout POS Panel -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between space-y-6 h-fit sticky top-20">
            <div>
                <h2 class="font-bold text-slate-900 text-lg border-b border-slate-100 pb-3 mb-4">💳 Checkout Pelunasan</h2>

                <form id="checkoutForm" action="<?php echo e(route('kasir.payment.process')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">No. Pesanan Dipilih</label>
                        <input type="text" id="selected_no_pesanan" name="no_pesanan" readonly required placeholder="Klik kartu meja di kiri"
                            class="w-full border border-slate-300 rounded-xl p-3 bg-slate-50 font-mono text-sm font-bold text-slate-900 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Metode Pembayaran</label>
                        <select name="metode_bayar" required class="w-full border border-slate-300 rounded-xl p-3 bg-white font-semibold text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="tunai">💵 Tunai (Cash)</option>
                            <option value="qris">📱 QRIS / E-Wallet</option>
                            <option value="debit">💳 Kartu Debit</option>
                            <option value="kredit">💳 Kartu Kredit</option>
                        </select>
                    </div>

                    <!-- Total & Payment Input -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <div class="flex justify-between items-center text-xs text-slate-600">
                            <span>Total Tagihan:</span>
                            <span id="display_total" class="font-extrabold font-mono text-base text-slate-900">Rp 0</span>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Jumlah Uang Dibayar (Rp)</label>
                            <input type="number" id="jumlah_bayar" name="jumlah_bayar" min="0" required oninput="calcKembalian()"
                                class="w-full border border-slate-300 rounded-xl p-3 bg-white font-mono text-base font-extrabold text-blue-600 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Quick Cash Presets -->
                        <div class="grid grid-cols-3 gap-1.5 pt-1">
                            <button type="button" onclick="setCashPreset('pas')" class="text-[10px] font-bold bg-white border p-1.5 rounded hover:bg-slate-100">Pas</button>
                            <button type="button" onclick="setCashPreset(50000)" class="text-[10px] font-bold bg-white border p-1.5 rounded hover:bg-slate-100">50.000</button>
                            <button type="button" onclick="setCashPreset(100000)" class="text-[10px] font-bold bg-white border p-1.5 rounded hover:bg-slate-100">100.000</button>
                        </div>

                        <div class="flex justify-between items-center text-xs pt-2 border-t border-slate-200">
                            <span class="font-semibold text-slate-600">Kembalian:</span>
                            <span id="display_kembalian" class="font-black font-mono text-base text-emerald-600">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-md transition text-sm flex items-center justify-center space-x-2">
                        <span>✅ Proses Pelunasan & Release Meja</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    let currentTotal = 0;

    function selectOrder(noPesanan, noMeja, total) {
        document.getElementById('selected_no_pesanan').value = noPesanan;
        currentTotal = parseFloat(total) || 0;
        document.getElementById('display_total').innerText = 'Rp ' + currentTotal.toLocaleString('id-ID');
        document.getElementById('jumlah_bayar').value = currentTotal;
        calcKembalian();
    }

    function setCashPreset(val) {
        if (val === 'pas') {
            document.getElementById('jumlah_bayar').value = currentTotal;
        } else {
            document.getElementById('jumlah_bayar').value = val;
        }
        calcKembalian();
    }

    function calcKembalian() {
        const bayar = parseFloat(document.getElementById('jumlah_bayar').value) || 0;
        const kembalian = Math.max(0, bayar - currentTotal);
        document.getElementById('display_kembalian').innerText = 'Rp ' + kembalian.toLocaleString('id-ID');
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ArtvAdmirer\.gemini\antigravity\scratch\siresto\resources\views/kasir/kasir.blade.php ENDPATH**/ ?>