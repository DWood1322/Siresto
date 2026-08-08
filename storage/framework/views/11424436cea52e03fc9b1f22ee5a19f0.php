<?php $__env->startSection('title', 'Notifikasi Masakan Siap Diantar — Pelayan SIResto'); ?>

<?php $__env->startSection('content'); ?>
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
        <a href="<?php echo e(route('pelayan.order.form')); ?>" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl transition">
            📋 Kembali ke Form Pemesanan POS
        </a>
    </div>

    <!-- Active Ready Orders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $pesananSiapDisajikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $readyOrder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white text-slate-800 p-5 rounded-2xl shadow-sm border-2 border-emerald-400 flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xl font-black text-slate-900">MEJA #<?php echo e($readyOrder->no_meja); ?></span>
                        <span class="text-xs bg-emerald-100 text-emerald-800 font-extrabold px-2.5 py-1 rounded-full">SIAP SAJI</span>
                    </div>
                    <p class="text-xs text-slate-400 font-mono mb-3"><?php echo e($readyOrder->no_pesanan); ?></p>

                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b pb-1 mb-2">Item Masakan:</p>
                    <ul class="text-xs space-y-2 text-slate-700">
                        <?php $__currentLoopData = $readyOrder->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex justify-between items-center bg-slate-50 p-2 rounded-lg border border-slate-100">
                                <span class="font-bold text-slate-900"><?php echo e($item->jumlah); ?>x <?php echo e($item->menu->nama_menu); ?></span>
                                <span class="text-emerald-600 font-semibold text-[11px]">✓ Selesai</span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                <form action="<?php echo e(route('pelayan.order.serve', $readyOrder->no_pesanan)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold py-3 px-4 rounded-xl transition flex items-center justify-center space-x-1 shadow">
                        <span>🍽️ Konfirmasi Makanan Telah Diantar ke Meja</span>
                    </button>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full bg-white p-16 text-center text-slate-400 rounded-2xl border border-dashed border-slate-300 space-y-2">
                <p class="font-bold text-base text-slate-700">Tidak ada masakan yang menunggu diantar saat ini.</p>
                <p class="text-xs text-slate-400">Begitu Koki menyelesaikan pesanan, notifikasi akan otomatis muncul di halaman ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ArtvAdmirer\.gemini\antigravity\scratch\siresto\resources\views/pelayan/ready_orders.blade.php ENDPATH**/ ?>