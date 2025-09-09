<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Welcome | INDONESIA AI IN BOX</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
</head>

<body class="bg-gray-900 text-white">

    <!-- Hero -->
    <section class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 flex flex-col justify-center items-center px-6 text-center">
        <img src="/icons/Logo.png" alt="Logo" class="w-20 h-20 mb-4 animate-bounce">
        <h1 class="text-4xl md:text-5xl font-extrabold text-blue-400 mb-4">INDONESIA AI IN BOX</h1>
        <p class="text-gray-300 text-lg mb-6 max-w-xl">Platform manajemen container cerdas untuk riset, edukasi, dan implementasi AI yang efisien dan aman.</p>
        <div class="flex gap-4 mb-12">
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded text-white font-medium shadow-lg transition">Login</a>
            <a href="{{ route('register') }}" class="border border-blue-500 hover:bg-blue-500 px-6 py-3 rounded text-white font-medium transition">Register</a>
        </div>
        <div class="animate-bounce text-gray-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </section>

    <!-- Fitur -->
    <section class="py-20 px-6 bg-gray-800">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-blue-300 mb-12">Fitur Utama</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-700 p-6 rounded shadow hover:shadow-xl transition">
                    <div class="text-4xl mb-3">📊</div>
                    <h3 class="text-xl font-semibold mb-2">Monitoring Real-time</h3>
                    <p class="text-sm text-gray-300">Pantau CPU, RAM, dan penggunaan Disk kontainer secara langsung.</p>
                </div>
                <div class="bg-gray-700 p-6 rounded shadow hover:shadow-xl transition">
                    <div class="text-4xl mb-3">🔒</div>
                    <h3 class="text-xl font-semibold mb-2">Manajemen Peran</h3>
                    <p class="text-sm text-gray-300">Role-based access untuk Admin, Pengguna, dan Maintenance.</p>
                </div>
                <div class="bg-gray-700 p-6 rounded shadow hover:shadow-xl transition">
                    <div class="text-4xl mb-3">📁</div>
                    <h3 class="text-xl font-semibold mb-2">Log Container</h3>
                    <p class="text-sm text-gray-300">Tinjau riwayat aktivitas dan log setiap container.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kenapa memilih kami -->
    <section class="py-20 px-6 bg-gray-900">
        <div class="max-w-5xl mx-auto text-center mb-12">
            <h2 class="text-3xl font-bold text-blue-400 mb-4">Kenapa Memilih Kami?</h2>
            <p class="text-gray-300">Dirancang untuk kebutuhan edukasi, riset, dan implementasi AI yang efisien, aman, dan mudah digunakan.</p>
        </div>
        <div class="grid md:grid-cols-2 gap-8 max-w-6xl mx-auto">
            <div class="bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-blue-300 mb-2">🔧 Sinkronisasi Docker Otomatis</h3>
                <p class="text-gray-300 text-sm">Setiap aksi (start, stop, hapus) langsung disinkronisasikan ke Docker Host.</p>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-blue-300 mb-2">⚡ Performa Cepat & Stabil</h3>
                <p class="text-gray-300 text-sm">Didukung Laravel, Alpine.js, dan Docker API untuk performa tinggi.</p>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-blue-300 mb-2">👥 Multi User Role</h3>
                <p class="text-gray-300 text-sm">Dashboard disesuaikan sesuai peran: Admin, User, Maintenance.</p>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-blue-300 mb-2">📦 Dukungan Berbagai Image</h3>
                <p class="text-gray-300 text-sm">Dapat menjalankan container dengan berbagai image (Python, Nginx, AI Tools, dsb).</p>
            </div>
        </div>
    </section>

    <!-- Dukungan Teknologi -->
    <section class="py-16 bg-gray-800 px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-blue-300">Teknologi yang Digunakan</h2>
        </div>
        <div class="flex flex-wrap justify-center gap-8 text-gray-300">
            <span class="bg-gray-700 px-6 py-3 rounded shadow">Laravel</span>
            <span class="bg-gray-700 px-6 py-3 rounded shadow">Docker</span>
            <span class="bg-gray-700 px-6 py-3 rounded shadow">Tailwind CSS</span>
            <span class="bg-gray-700 px-6 py-3 rounded shadow">Chart.js</span>
            <span class="bg-gray-700 px-6 py-3 rounded shadow">Alpine.js</span>
        </div>
    </section>

    <!-- FAQ Mini -->
    <section class="py-20 px-6 bg-gray-900">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h2 class="text-3xl font-bold text-blue-400 mb-4">Pertanyaan Umum</h2>
        </div>
        <div class="space-y-6 max-w-3xl mx-auto text-gray-300">
            <div>
                <h3 class="text-lg font-semibold text-blue-300">Apakah aplikasi ini open source?</h3>
                <p class="text-sm">Untuk sekarang belum, namun kami terbuka untuk kolaborasi dan pengembangan lanjutan.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-blue-300">Apakah bisa jalan di server lokal?</h3>
                <p class="text-sm">Ya. Sistem ini bisa dijalankan di server lokal, VPS, maupun cloud dengan Docker Host.</p>
            </div>
        </div>
    </section>

    <!-- Tim Pengembang -->
    <section class="py-20 px-6 bg-gray-800">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-300 mb-4">Tentang Tim Pengembang</h2>
            <p class="text-gray-300">Sistem ini dikembangkan oleh tim mahasiswa informatika yang peduli terhadap efektivitas pembelajaran teknologi kontainerisasi dan AI di Indonesia.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-center text-sm text-gray-400 py-6 border-t border-gray-700">
        <p>&copy; {{ date('Y') }} Container Management Dashboard. All rights reserved.</p>
        <p class="mt-1">Dibuat oleh tim <span class="text-blue-400 font-semibold">INDONESIA AI IN BOX</span>.</p>
    </footer>

</body>

</html>
