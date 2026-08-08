<?php $__env->startSection('title', 'Order POS Pelayan — SIResto'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header POS Pelayan -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Pelayan POS</span>
                <h1 class="text-xl font-bold text-slate-900">Antarmuka Pemesanan Restoran</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Pilih meja, tamu, dan pilih menu yang dipesan. Menu dengan status <strong>STOK HABIS</strong> tidak dapat ditambahkan.</p>
        </div>
        <div class="flex items-center space-x-3 text-xs">
            <span class="bg-emerald-100 text-emerald-800 font-bold px-3 py-1.5 rounded-lg border border-emerald-300 flex items-center space-x-1">
                <span>🔔</span>
                <span>Siap Diantar: <strong><?php echo e($pesananSiapDisajikan->count()); ?> Meja</strong></span>
            </span>
            <span class="bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg border font-medium">
                Meja Kosong: <strong><?php echo e($meja->count()); ?></strong> / <?php echo e($allMeja->count()); ?>

            </span>
        </div>
    </div>

    <!-- NOTIFIKASI INFORMASI MASAKAN SIAP DISAJIKAN -->
    <?php if($pesananSiapDisajikan->count() > 0): ?>
        <div class="bg-emerald-900 text-white p-6 rounded-2xl shadow-md space-y-4">
            <div class="flex justify-between items-center border-b border-emerald-800 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="animate-bounce text-xl">🔔</span>
                    <h2 class="text-lg font-black text-emerald-300">INFO DAPUR: <?php echo e($pesananSiapDisajikan->count()); ?> Pesanan Selesai Dimasak!</h2>
                </div>
                <a href="<?php echo e(route('pelayan.ready')); ?>" class="text-xs bg-emerald-800 hover:bg-emerald-700 text-emerald-100 px-3 py-1 rounded-full font-bold">
                    Lihat Layar Notifikasi ➔
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main POS Grid Layout -->
    <form action="<?php echo e(route('pelayan.order.store')); ?>" method="POST" id="posForm" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <?php echo csrf_field(); ?>

        <!-- LEFT COLUMN (2 Cols): Menu Catalog & Selector -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Meja & Tamu Selection Bar -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Pilih No. Meja</label>
                    <select name="no_meja" required class="w-full border border-slate-300 rounded-xl p-3 bg-slate-50 text-sm font-semibold focus:ring-2 focus:ring-amber-500 focus:bg-white transition">
                        <option value="">-- Pilih Meja Kosong --</option>
                        <?php $__currentLoopData = $meja; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($m->no_meja); ?>">Meja <?php echo e($m->no_meja); ?> (Kapasitas: <?php echo e($m->kapasitas_kursi); ?> Kursi)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jumlah Tamu (Orang)</label>
                    <input type="number" name="jumlah_tamu" value="2" min="1" required class="w-full border border-slate-300 rounded-xl p-3 bg-slate-50 text-sm font-semibold focus:ring-2 focus:ring-amber-500 focus:bg-white transition">
                </div>
            </div>

            <!-- Category Filter Tabs -->
            <div class="flex items-center space-x-2 overflow-x-auto pb-1" id="categoryFilter">
                <button type="button" onclick="filterCategory('all')" class="cat-btn active px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    Semua Menu
                </button>
                <button type="button" onclick="filterCategory('Makanan')" class="cat-btn px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold rounded-xl border border-slate-200 transition">
                    🍲 Makanan
                </button>
                <button type="button" onclick="filterCategory('Minuman')" class="cat-btn px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold rounded-xl border border-slate-200 transition">
                    🍹 Minuman
                </button>
                <button type="button" onclick="filterCategory('Cemilan')" class="cat-btn px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold rounded-xl border border-slate-200 transition">
                    🍟 Cemilan
                </button>
                <button type="button" onclick="filterCategory('Dessert')" class="cat-btn px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold rounded-xl border border-slate-200 transition">
                    🍰 Dessert
                </button>
            </div>

            <!-- Menu Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php $__currentLoopData = $menuList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isHabis = $menu->status_ketersediaan === 'habis';
                    ?>
                    <div class="menu-card rounded-2xl shadow-sm p-4 border flex flex-col justify-between transition
                        <?php echo e($isHabis ? 'bg-slate-100 border-red-200 text-slate-400 opacity-80' : 'bg-white border-slate-200 hover:border-amber-400 text-slate-900'); ?>"
                        data-category="<?php echo e($menu->kategori); ?>">

                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-200 text-slate-600 border">
                                    <?php echo e($menu->kategori); ?>

                                </span>
                                <?php if($isHabis): ?>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-red-600 text-white shadow-sm">
                                        🔴 STOK HABIS
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs font-mono font-bold text-slate-400">#<?php echo e($menu->kode_menu); ?></span>
                                <?php endif; ?>
                            </div>
                            <h3 class="font-bold text-base leading-snug mb-1 <?php echo e($isHabis ? 'line-through text-slate-500' : 'text-slate-900'); ?>">
                                <?php echo e($menu->nama_menu); ?>

                            </h3>
                            <p class="font-extrabold font-mono text-base mb-3 <?php echo e($isHabis ? 'text-slate-400' : 'text-amber-600'); ?>">
                                Rp <?php echo e(number_format($menu->harga, 0, ',', '.')); ?>

                            </p>
                        </div>

                        <!-- Quantity Selector Controls (Disabled if Habis) -->
                        <div class="space-y-2 pt-2 border-t border-slate-200">
                            <input type="hidden" name="items[<?php echo e($index); ?>][kode_menu]" value="<?php echo e($menu->kode_menu); ?>">
                            <div class="flex items-center justify-between p-1.5 rounded-xl border <?php echo e($isHabis ? 'bg-slate-200 border-slate-300' : 'bg-slate-50 border-slate-200'); ?>">
                                <button type="button" onclick="updateQty(<?php echo e($index); ?>, -1)" <?php echo e($isHabis ? 'disabled' : ''); ?>

                                    class="w-8 h-8 rounded-lg bg-white border border-slate-300 font-bold text-slate-700 flex items-center justify-center transition <?php echo e($isHabis ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-200'); ?>">-</button>

                                <input type="number" id="qty_<?php echo e($index); ?>" name="items[<?php echo e($index); ?>][jumlah]" value="0" min="0" readonly
                                    class="w-12 text-center bg-transparent font-extrabold text-sm <?php echo e($isHabis ? 'text-slate-400' : 'text-slate-900'); ?> focus:outline-none"
                                    data-price="<?php echo e($menu->harga); ?>" data-name="<?php echo e($menu->nama_menu); ?>" data-status="<?php echo e($menu->status_ketersediaan); ?>">

                                <button type="button" onclick="updateQty(<?php echo e($index); ?>, 1)" <?php echo e($isHabis ? 'disabled' : ''); ?>

                                    class="w-8 h-8 rounded-lg font-bold text-white flex items-center justify-center shadow-sm transition <?php echo e($isHabis ? 'bg-slate-400 opacity-40 cursor-not-allowed' : 'bg-amber-500 hover:bg-amber-600'); ?>">+</button>
                            </div>

                            <?php if($isHabis): ?>
                                <p class="text-[10px] font-bold text-red-600 italic text-center py-1">⚠️ Bahan baku di dapur kosong</p>
                            <?php else: ?>
                                <input type="text" name="items[<?php echo e($index); ?>][catatan]" placeholder="Catatan kustom (misal: pedas)"
                                    class="w-full text-xs p-2 border border-slate-200 rounded-lg bg-white focus:ring-1 focus:ring-amber-500 focus:outline-none">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- RIGHT COLUMN (1 Col): Order Basket Summary -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between space-y-6 h-fit sticky top-20">
            <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h2 class="font-bold text-slate-900 text-lg">🛒 Ringkasan Pesanan</h2>
                    <span id="cartCountBadge" class="text-xs bg-amber-100 text-amber-800 font-bold px-2.5 py-0.5 rounded-full">0 Item Dipilih</span>
                </div>

                <!-- Selected Items List -->
                <div id="cartItemsList" class="space-y-3 max-h-72 overflow-y-auto pr-1">
                    <p id="emptyCartMsg" class="text-xs text-slate-400 text-center py-8 italic">
                        Belum ada item dipilih. Tekan tombol (+) pada menu di sebelah kiri.
                    </p>
                </div>
            </div>

            <!-- Total Price & Submit Button -->
            <div class="pt-4 border-t border-slate-200 space-y-4">
                <div class="flex justify-between items-center text-sm font-semibold">
                    <span class="text-slate-500">Total Tagihan Estimasi:</span>
                    <span id="grandTotalText" class="text-2xl font-black font-mono text-emerald-600">Rp 0</span>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-md transition text-sm flex items-center justify-center space-x-2">
                    <span>🚀 Kirim Pesanan ke Dapur</span>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Interactive JS Script for POS Cart & Category Filtering -->
<script>
    function updateQty(index, change) {
        const input = document.getElementById('qty_' + index);
        if (input.getAttribute('data-status') === 'habis') return;

        let val = parseInt(input.value) || 0;
        val = Math.max(0, val + change);
        input.value = val;
        renderCart();
    }

    function renderCart() {
        const list = document.getElementById('cartItemsList');
        const countBadge = document.getElementById('cartCountBadge');
        const grandTotalText = document.getElementById('grandTotalText');

        let totalItems = 0;
        let grandTotal = 0;
        let itemsHtml = '';

        const inputs = document.querySelectorAll('input[id^="qty_"]');
        inputs.forEach(input => {
            const qty = parseInt(input.value) || 0;
            if (qty > 0 && input.getAttribute('data-status') === 'tersedia') {
                totalItems += qty;
                const price = parseFloat(input.getAttribute('data-price')) || 0;
                const name = input.getAttribute('data-name');
                const subtotal = price * qty;
                grandTotal += subtotal;

                itemsHtml += `
                    <div class="flex items-center justify-between text-xs bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                        <div>
                            <span class="font-bold text-slate-800">${name}</span>
                            <p class="text-slate-500 font-mono">${qty} x Rp ${price.toLocaleString('id-ID')}</p>
                        </div>
                        <span class="font-bold font-mono text-slate-900">Rp ${subtotal.toLocaleString('id-ID')}</span>
                    </div>
                `;
            }
        });

        countBadge.innerText = totalItems + ' Item Dipilih';
        grandTotalText.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

        if (totalItems > 0) {
            list.innerHTML = itemsHtml;
        } else {
            list.innerHTML = '<p id="emptyCartMsg" class="text-xs text-slate-400 text-center py-8 italic">Belum ada item dipilih. Tekan tombol (+) pada menu di sebelah kiri.</p>';
        }
    }

    function filterCategory(cat) {
        const cards = document.querySelectorAll('.menu-card');
        const btns = document.querySelectorAll('.cat-btn');

        btns.forEach(btn => {
            btn.classList.remove('bg-slate-900', 'text-white');
            btn.classList.add('bg-white', 'text-slate-700');
        });

        event.target.classList.remove('bg-white', 'text-slate-700');
        event.target.classList.add('bg-slate-900', 'text-white');

        cards.forEach(card => {
            if (cat === 'all' || card.getAttribute('data-category') === cat) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ArtvAdmirer\.gemini\antigravity\scratch\siresto\resources\views/pelayan/order_form.blade.php ENDPATH**/ ?>