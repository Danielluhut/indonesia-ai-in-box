<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 flex flex-col text-white">

        <!-- Main Content -->
        <div class="flex-grow flex items-center justify-center">
            <div class="bg-gray-800 border border-blue-600 rounded-lg px-8 py-10 w-full max-w-md text-center shadow-lg">

                <!-- Logo Besar Tengah -->
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="w-16 h-16"> <!-- Logo besar tengah -->
                </div>

                <!-- Judul -->
                <h1 class="text-xl font-bold text-blue-400 mb-2">Install Container Package</h1>
                <p class="text-sm text-gray-300 mb-6">Pilih package yang ingin kamu install dan masukkan license key</p>

                <!-- Alert -->
                @if (session('success'))
                    <div id="success-alert" class="mb-4 px-4 py-2 bg-green-600 text-white rounded shadow">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div id="error-alert" class="mb-4 px-4 py-2 bg-red-600 text-white rounded shadow">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('user.package.install') }}">
                    @csrf

                    <div class="text-left text-sm text-gray-300 mb-2">Pilih Package</div>
                    <select name="id" required
                        class="w-full p-2 mb-4 rounded border border-gray-600 bg-gray-700 text-white">
                        <option value="">-- Pilih Package --</option>
                        @foreach ($packages as $pkg)
                            <option value="{{ $pkg['id'] }}">{{ $pkg['name'] ?? 'Unnamed' }}</option>
                        @endforeach
                    </select>

                    <div class="text-left text-sm text-gray-300 mb-2">License Key</div>
                    <input type="text" name="key" placeholder="Contoh: 1234567890" required
                        class="w-full p-2 mb-6 rounded border border-gray-600 bg-gray-700 text-white" />

                    <div class="flex justify-between items-center">
                        <a href="{{ route('user.dashboard') }}" class="text-sm text-blue-400 hover:underline">
                            ← Kembali ke Dashboard
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-semibold">
                            Install
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Auto-hide alert -->
    <script>
        setTimeout(() => {
            const alerts = document.querySelectorAll('#success-alert, #error-alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    </script>
</x-app-layout>