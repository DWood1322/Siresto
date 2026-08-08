<?php $__env->startSection('title', 'KDS Dapur — Koki SIResto'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header KDS Dapur Koki -->
    <div class="bg-slate-900 text-white p-5 rounded-2xl shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="bg-red-600 text-white text-xs font-black px-2.5 py-1 rounded-full uppercase">Sub-Proses 1: KDS DAPUR</span>
                <h1 class="text-xl font-bold">Kitchen Display System (Layar Antrean Dapur)</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Daftar masakan masuk. Ketika masakan selesai, cukup tekan 1 tombol hijau <strong>"Masakan Selesai (Siap Saji)"</strong>.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="bg-slate-800 text-emerald-400 font-mono text-xs px-3 py-2 rounded-xl border border-slate-700 font-bold">
                🔥 <?php echo e($pesananAktif->count()); ?> Order Masuk
            </span>
            <a href="<?php echo e(route('koki.menu')); ?>" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-xl border border-slate-700 transition">
                📦 2. Kelola Stok Menu ➔
            </a>
        </div>
    </div>

    <!-- Active Orders Grid for Koki -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $pesananAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl shadow-sm border-2 border-slate-200 overflow-hidden flex flex-col justify-between hover:border-red-500 transition">
                <!-- Header Card Meja & Waktu Masuk -->
                <div class="bg-slate-900 text-white px-5 py-4 flex justify-between items-center">
                    <div>
                        <span class="text-2xl font-black text-amber-400">MEJA #<?php echo e($order->no_meja); ?></span>
                        <p class="text-[11px] text-slate-400 font-mono"><?php echo e($order->no_pesanan); ?></p>
                    </div>
                    <div class="text-right">
                        <span class="px-2.5 py-1 text-xs font-black rounded uppercase block mb-1
                            <?php echo e($order->status_pesanan === 'pending' ? 'bg-amber-500 text-slate-900' : 'bg-blue-600 text-white'); ?>">
                            <?php echo e(strtoupper($order->status_pesanan)); ?>

                        </span>
                        <span class="text-[10px] text-slate-300 font-mono">🕒 <?php echo e(\Carbon\Carbon::parse($order->tgl_pesanan)->format('H:i')); ?></span>
                    </div>
                </div>

                <!-- Menu Items List -->
                <div class="p-5 space-y-4 flex-1 bg-white">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b pb-1">Daftar Masakan yang Dipesan:</p>

                    <ul class="space-y-3">
                        <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex items-start justify-between bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-red-600 text-white font-black text-base px-2 py-0.5 rounded-lg shadow-sm">
                                            <?php echo e($item->jumlah); ?>x
                                        </span>
                                        <span class="font-black text-slate-900 text-base leading-tight">
                                            <?php echo e($item->menu->nama_menu); ?>

                                        </span>
                                    </div>
                                    <?php if($item->catatan): ?>
                                        <div class="bg-amber-100 text-amber-900 text-xs font-bold px-2 py-1 rounded-md inline-block">
                                            Catatan: "<?php echo e($item->catatan); ?>"
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <span class="text-[10px] font-bold uppercase px-2 py-1 rounded
                                    <?php echo e($item->status_item === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'); ?>">
                                    <?php echo e($item->status_item); ?>

                                </span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                <!-- 1-CLICK SELESAI BUTTON -->
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    <form action="<?php echo e(route('koki.order.complete', $order->no_pesanan)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-black py-3.5 px-4 rounded-xl shadow-md transition text-sm flex items-center justify-center space-x-2">
                            <span>✅ MASAKAN SELESAI (SIAP SAJI)</span>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full bg-white p-16 rounded-2xl border-2 border-dashed border-slate-300 text-center space-y-2">
                <p class="text-xl font-bold text-slate-700">Dapur Bersih! Tidak ada antrean masakan aktif saat ini.</p>
                <p class="text-xs text-slate-400">Pesanan baru yang dikirim oleh Pelayan akan otomatis muncul di layar ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ArtvAdmirer\.gemini\antigravity\scratch\siresto\resources\views/koki/kds.blade.php ENDPATH**/ ?>