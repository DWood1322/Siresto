<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIResto POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 antialiased font-sans">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mb-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">SIResto POS</h1>
            <p class="text-sm text-slate-500">Sistem Informasi Restaurant & Kasir POS</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-xs rounded">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Username</label>
                <input type="text" name="username" value="<?php echo e(old('username')); ?>" required autofocus
                    placeholder="Masukkan username"
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required
                    placeholder="••••••••"
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="remember" class="rounded text-emerald-600 focus:ring-emerald-500">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition text-sm">
                Masuk ke Sistem
            </button>
        </form>

        <!-- Quick Demo Account Credentials Info -->
        <div class="border-t border-slate-100 pt-4 text-xs text-slate-500 space-y-1">
            <p class="font-semibold text-slate-700 text-center mb-2">Pilih Akun Demo (Password: <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-800">password</code>):</p>
            <div class="grid grid-cols-2 gap-2 text-center text-[11px]">
                <div class="bg-amber-50 p-2 rounded border border-amber-200 text-amber-900">
                    <span class="font-bold block">Pelayan</span>
                    <code>pelayan1</code>
                </div>
                <div class="bg-red-50 p-2 rounded border border-red-200 text-red-900">
                    <span class="font-bold block">Koki</span>
                    <code>koki1</code>
                </div>
                <div class="bg-blue-50 p-2 rounded border border-blue-200 text-blue-900">
                    <span class="font-bold block">Kasir</span>
                    <code>kasir1</code>
                </div>
                <div class="bg-emerald-50 p-2 rounded border border-emerald-200 text-emerald-900">
                    <span class="font-bold block">Pemilik (Bos)</span>
                    <code>pemilik1</code>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
<?php /**PATH C:\Users\ArtvAdmirer\.gemini\antigravity\scratch\siresto\resources\views/auth/login.blade.php ENDPATH**/ ?>