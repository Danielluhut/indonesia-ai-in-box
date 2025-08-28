@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <div x-data="{ search: '' }"
        class="p-6 text-white min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <h1 class="text-3xl font-bold mb-6">Maintenance Dashboard — Container Monitor</h1>

        @if (session('status'))
            <div id="status-alert" class="mb-4 text-green-400 font-semibold transition-opacity duration-500">
                {{ session('status') }}
            </div>

            <script>
                setTimeout(() => {
                    const alert = document.getElementById('status-alert');
                    if (alert) {
                        alert.style.transition = 'opacity 0.5s ease-out';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 3000);
            </script>
        @endif

        <!-- Monitoring Panel -->
        <div class="flex flex-wrap gap-6 text-sm text-gray-300 mb-6">
            <div class="bg-gray-800 p-4 rounded w-52">
                <div class="text-gray-400">CPU Usage</div>
                <div class="text-xl font-bold" id="cpu-usage">--%</div>
            </div>
            <div class="bg-gray-800 p-4 rounded w-52">
                <div class="text-gray-400">RAM Usage</div>
                <div class="text-xl font-bold" id="ram-usage">-- GB</div>
            </div>
            <div class="bg-gray-800 p-4 rounded w-52">
                <div class="text-gray-400">Disk Usage</div>
                <div class="text-xl font-bold" id="disk-usage">-- GB</div>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-4">
            <input type="text" placeholder="Cari container..." x-model="search"
                class="px-4 py-2 rounded-md bg-gray-800 text-white border border-blue-500 w-full md:w-1/3" />
        </div>

        <!-- Table -->
        <!-- Services -->
        @foreach ($containers as $service)
            @if (!empty($service['container_data']) && is_array($service['container_data']))
                <div x-data="{ open: false }" class="bg-gray-900 mb-4 rounded shadow">
                    <!-- Header Service -->
                    <div x-data="{ open: false }" class="bg-gray-900 mb-4 rounded shadow">
                        <div
                            class="w-full flex flex-wrap md:flex-nowrap justify-between items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-t">
                            <div @click="open = !open" class="flex items-center gap-2 font-semibold cursor-pointer">
                                <svg :class="open ? 'rotate-90' : ''" class="w-4 h-4 transform transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                {{ $service['service'] ?? '(Unknown Service)' }}
                                <span class="text-sm text-gray-400 ml-2">Machine: {{ $service['machine'] ?? '-' }}</span>
                            </div>

                            <form method="POST" action="{{ route('maintenance.container.restartAll') }}">
                                @csrf
                                <input type="hidden" name="ids"
                                    value='@json(collect($service["container_data"])->pluck("id_container")->all())'>
                                <button class="bg-yellow-600 hover:bg-yellow-700 px-3 py-1 rounded text-sm ml-4">
                                    🔁 Restart All
                                </button>
                            </form>
                        </div>


                        <!-- Container Table -->
                        <div x-show="open" x-collapse class="bg-gray-800 rounded-b">
                            <table class="w-full text-sm text-white">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="p-3 text-center">Nama</th>
                                        <th class="p-3 text-center">ID</th>
                                        <th class="p-3 text-center">Local IP</th>
                                        <th class="p-3 text-center">External IP</th>
                                        <th class="p-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-500">
                                    @foreach ($service['container_data'] as $container)
                                        @php
                                            $containerId = $container['id_container'] ?? '-';
                                            $containerName = $container['container_name'] ?? '-';
                                        @endphp
                                        <tr class="hover:bg-gray-700 transition">
                                            <td class="p-3 text-center">{{ $containerName }}</td>
                                            <td class="p-3 text-blue-400 text-center">
                                                <code>{{ Str::limit($containerId, 12, '') }}</code>
                                            </td>
                                            <td class="p-3 text-center">{{ $container['loc_ip'] ?? '-' }}</td>
                                            <td class="p-3 text-center">{{ $container['ext_ip'] ?? '-' }}</td>
                                            <td class="p-3 text-center">
                                                <div class="flex flex-wrap justify-center gap-2">
                                                    <form method="POST" action="{{ route('maintenance.container.restart') }}">
                                                        @csrf
                                                        <input type="hidden" name="id_container" value="{{ $containerId }}">
                                                        <button
                                                            class="bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded text-sm font-medium shadow">
                                                            🔁 Restart
                                                        </button>
                                                    </form>

                                                    <a href="{{ route('maintenance.container.log', $containerId) }}"
                                                        class="bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded text-sm font-medium shadow">
                                                        📄 Log
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            @endif
        @endforeach

            <!-- Script Monitor Dummy -->
            <script>
                function simulateMonitoring() {
                    document.getElementById('cpu-usage').textContent = Math.floor(Math.random() * 70) + '%';
                    document.getElementById('ram-usage').textContent = (Math.random() * 3 + 1).toFixed(1) + ' GB';
                    document.getElementById('disk-usage').textContent = (Math.random() * 80 + 10).toFixed(1) + ' GB';
                }
                setInterval(simulateMonitoring, 3000);
                simulateMonitoring();
            </script>
        </div>
</x-app-layout>