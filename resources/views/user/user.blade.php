@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <div class="p-6 text-white bg-gray-900 min-h-screen">
        <h1 class="text-2xl font-bold mb-6">User Dashboard: Services & Containers</h1>

        <div class="mb-6">
            <a href="{{ route('user.package.form') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white text-sm font-semibold shadow">
                ➕ Install Package
            </a>
        </div>

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
                }, 3000);
            </script>
        @endif

        <!-- Services -->
        <div class="bg-gray-800 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-4">Daftar Services (Containers)</h2>

            @forelse ($services as $service)
                @php
                    $uniqueServiceKey = 'user-service-' . ($service['name'] ?? 'unknown') . '-' . ($service['machine'] ?? 'no-machine');
                @endphp

                <div x-data="{ open: JSON.parse(localStorage.getItem('{{ $uniqueServiceKey }}')) ?? false }"
                    x-init="$watch('open', value => localStorage.setItem('{{ $uniqueServiceKey }}', JSON.stringify(value)))"
                    class="bg-gray-900 mb-4 rounded shadow">

                    <!-- Header Service -->
                    <div
                        class="w-full flex flex-wrap md:flex-nowrap justify-between items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-t">
                        <div @click="open = !open" class="flex items-center gap-2 font-semibold cursor-pointer">
                            <svg :class="open ? 'rotate-90' : ''" class="w-4 h-4 transform transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                            {{ $service['name'] }}
                            <span class="text-sm text-gray-400 ml-2">Machine: {{ $service['machine'] ?? '-' }}</span>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-2 md:mt-0">
                            @foreach (['start', 'stop'] as $action)
                                <form method="POST" action="{{ route('user.service.action') }}">
                                    @csrf
                                    <input type="hidden" name="Action" value="{{ $action }}">
                                    <input type="hidden" name="ids"
                                        value='@json(collect($service["containers"])->pluck("id")->all())'>
                                    <button class="px-3 py-1 rounded text-xs whitespace-nowrap
                                        {{ $action === 'start' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                                        {{ ucfirst($action) }} All
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tabel Container -->
                    <div x-show="open" x-collapse class="bg-gray-800 rounded-b">
                        <table class="w-full text-sm text-white">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="p-3 text-center">Container Name</th>
                                    <th class="p-3 text-center">ID</th>
                                    <th class="p-3 text-center">Local IP</th>
                                    <th class="p-3 text-center">External IP</th>
                                    <th class="p-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-500">
                                @foreach ($service['containers'] as $container)
                                    @php
                                        $containerId = $container['id'] ?? '-';
                                        $containerName = $container['name'] ?? '-';
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
                                                @foreach (['start', 'stop'] as $action)
                                                    <form method="POST" action="{{ route('user.containers.' . $action, $containerId) }}">
                                                        @csrf
                                                        <button class="px-3 py-1 rounded text-xs whitespace-nowrap
                                                            {{ $action === 'start' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
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
            @empty
                <p class="text-gray-400">Belum ada service dengan container untuk kamu.</p>
            @endforelse
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
