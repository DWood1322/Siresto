<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'SIResto POS System'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased min-h-screen flex flex-col">

    <!-- Global Navigation Header (Role Aware) -->
    <header class="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16 border-b border-slate-800">
            <!-- Brand -->
            <div class="flex items-center space-x-3">
                <a href="/" class="text-xl font-black tracking-wider text-emerald-400 flex items-center space-x-2">
                    <span>🍽️ SIResto POS</span>
                </a>
            </div>

            <!-- Main Role Selector -->
            <?php if(auth()->guard()->check()): ?>
                <nav class="hidden md:flex items-center space-x-2 text-xs font-semibold">
                    <?php if(Auth::user()->role === 'Pelayan' || Auth::user()->role === 'Pemilik Restoran'): ?>
                        <a href="<?php echo e(route('pelayan.order.form')); ?>"
                            class="px-3.5 py-2 rounded-xl transition <?php echo e(request()->routeIs('pelayan.*') ? 'bg-amber-500 text-slate-950 font-black shadow-md' : 'text-slate-300 hover:bg-slate-800'); ?>">
                            📋 Pelayan POS
                        </a>
                    <?php endif; ?>

                    <?php if(Auth::user()->role === 'Koki' || Auth::user()->role === 'Pemilik Restoran'): ?>
                        <a href="<?php echo e(route('koki.kds')); ?>"
                            class="px-3.5 py-2 rounded-xl transition <?php echo e(request()->routeIs('koki.*') ? 'bg-red-600 text-white font-black shadow-md' : 'text-slate-300 hover:bg-slate-800'); ?>">
                            🔥 Dapur Koki
                        </a>
                    <?php endif; ?>

                    <?php if(Auth::user()->role === 'Kasir' || Auth::user()->role === 'Pemilik Restoran'): ?>
                        <a href="<?php echo e(route('kasir.index')); ?>"
                            class="px-3.5 py-2 rounded-xl transition <?php echo e(request()->routeIs('kasir.*') ? 'bg-blue-600 text-white font-black shadow-md' : 'text-slate-300 hover:bg-slate-800'); ?>">
                            💳 Kasir POS
                        </a>
                    <?php endif; ?>

                    <?php if(Auth::user()->role === 'Pemilik Restoran'): ?>
                        <a href="<?php echo e(route('pemilik.dashboard')); ?>"
                            class="px-3.5 py-2 rounded-xl transition <?php echo e(request()->routeIs('pemilik.*') ? 'bg-emerald-600 text-white font-black shadow-md' : 'text-slate-300 hover:bg-slate-800'); ?>">
                            📊 Dashboard Bos
                        </a>
                    <?php endif; ?>
                </nav>

                <!-- User & Logout -->
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-100"><?php echo e(Auth::user()->nama_user); ?></p>
                        <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded font-bold
                            <?php echo e(Auth::user()->role === 'Pemilik Restoran' ? 'bg-emerald-500 text-slate-950' : ''); ?>

                            <?php echo e(Auth::user()->role === 'Pelayan' ? 'bg-amber-500 text-slate-950' : ''); ?>

                            <?php echo e(Auth::user()->role === 'Koki' ? 'bg-red-600 text-white' : ''); ?>

                            <?php echo e(Auth::user()->role === 'Kasir' ? 'bg-blue-600 text-white' : ''); ?>">
                            <?php echo e(Auth::user()->role); ?>

                        </span>
                    </div>

                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition">
                            🚪 <span class="hidden sm:inline text-xs ml-1">Keluar</span>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sub-Navigation Tabs Bar per Role -->
        <?php if(auth()->guard()->check()): ?>
            <div class="bg-slate-950 px-4 sm:px-6 lg:px-8 py-2">
                <div class="max-w-7xl mx-auto flex items-center space-x-3 text-xs">

                    <!-- Sub-Tabs Koki -->
                    <?php if(request()->routeIs('koki.*')): ?>
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Sub-Proses Koki:</span>
                        <a href="<?php echo e(route('koki.kds')); ?>"
                            class="px-3 py-1 rounded-lg transition <?php echo e(request()->routeIs('koki.kds') ? 'bg-red-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                            🔥 1. Layar Antrean Dapur (KDS)
                        </a>
                        <a href="<?php echo e(route('koki.menu')); ?>"
                            class="px-3 py-1 rounded-lg transition <?php echo e(request()->routeIs('koki.menu*') ? 'bg-red-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                            📦 2. Kelola Stok Menu & Bahan Baku
                        </a>
                    <?php endif; ?>

                    <!-- Sub-Tabs Pelayan -->
                    <?php if(request()->routeIs('pelayan.*')): ?>
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Sub-Proses Pelayan:</span>
                        <a href="<?php echo e(route('pelayan.order.form')); ?>"
                            class="px-3 py-1 rounded-lg transition <?php echo e(request()->routeIs('pelayan.order.*') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                            📋 1. Form Pemesanan POS
                        </a>
                        <a href="<?php echo e(route('pelayan.ready')); ?>"
                            class="px-3 py-1 rounded-lg transition <?php echo e(request()->routeIs('pelayan.ready') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                            🔔 2. Notifikasi Siap Diantar
                        </a>
                    <?php endif; ?>

                    <!-- Sub-Tabs Kasir -->
                    <?php if(request()->routeIs('kasir.*')): ?>
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Sub-Proses Kasir:</span>
                        <a href="<?php echo e(route('kasir.index')); ?>"
                            class="px-3 py-1 rounded-lg transition <?php echo e(request()->routeIs('kasir.index') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                            💳 1. Checkout POS Pelunasan
                        </a>
                        <a href="<?php echo e(route('kasir.history')); ?>"
                            class="px-3 py-1 rounded-lg transition <?php echo e(request()->routeIs('kasir.history') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                            📜 2. Riwayat Transaksi & Log
                        </a>
                    <?php endif; ?>

                    <!-- Sub-Tabs Pemilik -->
                    <?php if(request()->routeIs('pemilik.*')): ?>
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Sub-Proses Bos:</span>
                        <a href="<?php echo e(route('pemilik.dashboard')); ?>"
                            class="px-3 py-1 rounded-lg transition bg-emerald-600 text-white font-bold">
                            📊 Executive Overview & Validasi Laporan (D5)
                        </a>
                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        <?php if(session('success')): ?>
            <div class="mb-4 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-900 rounded-lg shadow-sm text-sm flex items-center justify-between">
                <span><?php echo e(session('success')); ?></span>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-900 rounded-lg shadow-sm text-sm">
                <ul class="list-disc pl-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-3 text-center text-xs text-slate-500">
        SIResto POS System &copy; <?php echo e(date('Y')); ?> — Hak Akses & Sub-Proses Role Terstruktur
    </footer>

</body>
</html>
<?php /**PATH C:\Users\ArtvAdmirer\.gemini\antigravity\scratch\siresto\resources\views/layouts/app.blade.php ENDPATH**/ ?>