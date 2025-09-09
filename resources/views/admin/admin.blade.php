<x-app-layout>
    <div class="p-6 text-white bg-gray-900 min-h-screen">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard: Machine, Package, Service</h1>

        @if (session('success'))
            <div id="success-alert" class="bg-green-700 text-white px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(() => {
                    const alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.style.transition = 'opacity 0.5s ease-out';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 3000); // Alert akan mulai menghilang setelah 3 detik
            </script>
        @endif

        <!-- Services -->
        <div class="bg-gray-800 p-4 rounded shadow mb-10">
            <h2 class="text-lg font-semibold mb-4">Daftar Services (Containers)</h2>

            @forelse($users as $user)
                <div class="mb-6">
                    <h3 class="text-xl font-bold mb-2 text-blue-400">
                        User: {{ $user->name ?? 'Unknown' }}
                    </h3>

                    @php
                        $services = $allContainers[$user->id] ?? [];
                    @endphp

                    @if (is_array($services) && count($services) > 0)
                        @foreach ($services as $service)
                            @if (!empty($service['container_data']) && is_array($service['container_data']))
                                @php
                                    $uniqueServiceKey = 'service-' . $user->id . '-' . ($service['service'] ?? 'unknown') . '-' . ($service['machine'] ?? 'no-machine');
                                @endphp
                                <div x-data="{ open: JSON.parse(localStorage.getItem('{{ $uniqueServiceKey }}')) ?? false }"
                                    x-init="$watch('open', value => localStorage.setItem('{{ $uniqueServiceKey }}', JSON.stringify(value)))"
                                    class="bg-gray-900 mb-4 rounded shadow">
                                    <div
                                        class="w-full flex flex-wrap md:flex-nowrap justify-between items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-t">
                                        <div @click="open = !open" class="flex items-center gap-2 font-semibold cursor-pointer">
                                            <svg :class="open ? 'rotate-90' : ''" class="w-4 h-4 transform transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                            {{ $service['service'] ?? '(Unknown Service)' }}
                                            <span class="text-sm text-gray-400 ml-2">Machine: {{ $service['machine'] ?? '-' }}</span>
                                        </div>

                                        <div class="flex flex-wrap gap-2 mt-2 md:mt-0">
                                            @foreach (['start', 'stop', 'restart', 'shutdown'] as $action)
                                                    <form method="POST" action="{{ route('admin.service.action') }}">
                                                        @csrf
                                                        <input type="hidden" name="Action" value="{{ $action }}">
                                                        <input type="hidden" name="ids"
                                                            value='@json(collect($service["container_data"])->pluck("id_container")->all())'>
                                                        <button class="px-3 py-1 rounded text-xs whitespace-nowrap
                                                                                                                                                                                                                                                {{ $action === 'start' ? 'bg-green-600 hover:bg-green-700' :
                                                ($action === 'stop' ? 'bg-red-600 hover:bg-red-700' :
                                                    ($action === 'restart' ? 'bg-yellow-600 hover:bg-yellow-700' :
                                                        'bg-gray-600 hover:bg-gray-700')) }}">
                                                            {{ ucfirst($action) }} All
                                                        </button>
                                                    </form>
                                            @endforeach
                                        </div>
                                    </div>

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
                                                                @foreach (['stop', 'restart', 'shutdown'] as $action)
                                                                                    <form method="POST" action="{{ route('admin.container.action') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="Action" value="{{ $action }}">
                                                                                        <input type="hidden" name="id_container" value="{{ $containerId }}">
                                                                                        <button class="px-3 py-1 rounded text-xs whitespace-nowrap
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {{ $action === 'stop' ? 'bg-red-600 hover:bg-red-700' :
                                                                    ($action === 'restart' ? 'bg-yellow-600 hover:bg-yellow-700' :
                                                                        'bg-gray-600 hover:bg-gray-700') }}">
                                                                                            {{ ucfirst($action) }}
                                                                                        </button>
                                                                                    </form>
                                                                @endforeach
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
                    @else
                        <p class="text-gray-400">Tidak ada container untuk user ini.</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-400">Belum ada user terdaftar.</p>
            @endforelse
        </div>

        <!-- Machines -->
        <div class="bg-gray-800 p-4 rounded shadow mb-10">
            <h2 class="text-lg font-semibold mb-4">Daftar Machine</h2>

            <form method="POST" action="{{ route('admin.machines.store') }}" class="mb-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="name" placeholder="Name" required class="p-2 rounded bg-gray-700">
                    <input type="text" name="location" placeholder="Location" required class="p-2 rounded bg-gray-700">
                    <input type="text" name="ip" placeholder="IP Address" required class="p-2 rounded bg-gray-700">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded mt-3">
                    Tambah Machine
                </button>
            </form>

            @if (is_array($machines) && count($machines) > 0)
                <table class="w-full text-sm text-white">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="p-3 text-center">Nama</th>
                            <th class="p-3 text-center">Lokasi</th>
                            <th class="p-3 text-center">IP</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-blue-500">
                        @foreach ($machines as $machine)
                            <tr>
                                <td class="p-3 text-center">{{ $machine['name'] ?? '-' }}</td>
                                <td class="p-3 text-center">{{ $machine['location'] ?? '-' }}</td>
                                <td class="p-3 text-center">{{ $machine['ip'] ?? '-' }}</td>
                                <td class="p-3 text-center">
                                    <form method="POST" action="{{ route('admin.machines.remove') }}">
                                        @csrf
                                        <input type="hidden" name="ip" value="{{ $machine['ip'] ?? '' }}">
                                        <input type="hidden" name="name" value="{{ $machine['name'] ?? '' }}">
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-400">Belum ada machine terdaftar.</p>
            @endif
        </div>

        <!-- Packages -->
        <div class="bg-gray-800 p-4 rounded shadow mb-10">
            <h2 class="text-lg font-semibold mb-4">Daftar Packages</h2>
            @if (is_array($packages) && count($packages) > 0)
                <table class="w-full text-sm text-white border border-blue-500">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="p-3 text-center">ID</th>
                            <th class="p-3 text-center">Nama Package</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-blue-500">
                        @foreach ($packages as $package)
                            <tr>
                                <td class="p-3 text-center">{{ $package['id'] ?? '-' }}</td>
                                <td class="p-3 text-center">{{ $package['name'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-400">Belum ada package tersedia.</p>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 800);
</script>