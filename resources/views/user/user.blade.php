@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <div class="p-6 text-white min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <h1 class="text-3xl font-bold mb-6 text-blue-400">User Dashboard — Services & Containers</h1>
        <div class="mb-6">
            <a href="{{ route('user.package.form') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white text-sm font-semibold shadow">
                ➕ Install Package
            </a>
        </div>

        @if (session('success'))
            <div id="success-alert" class="mb-4 px-4 py-3 bg-green-600 text-white rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($services as $service)
                @php $shouldOpen = session('open_service') === $service['name']; @endphp
                <div x-data="{ open: {{ $shouldOpen ? 'true' : 'false' }} }" class="bg-gray-800 rounded shadow">
                    <div
                        class="w-full flex flex-wrap md:flex-nowrap justify-between items-center px-4 py-3 bg-gray-700 hover:bg-gray-600 rounded-t">
                        <div @click="open = !open" class="flex items-center gap-2 font-semibold cursor-pointer">
                            <svg :class="open ? 'rotate-90' : ''" class="w-4 h-4 transform transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            <span>{{ $service['name'] }}</span>
                            <span class="text-sm text-gray-400 ml-2">Machine: {{ $service['machine'] }}</span>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-2 md:mt-0">
                            @foreach (['start', 'stop'] as $action)
                                <form method="POST" action="{{ route('user.service.action') }}">
                                    @csrf
                                    <input type="hidden" name="Action" value="{{ $action }}">
                                    <input type="hidden" name="ids" value='@json(collect($service["containers"])->pluck("id")->all())'>
                                    <input type="hidden" name="open_service" value="{{ $service['name'] }}">
                                    <button
                                        class="px-3 py-1 rounded text-xs whitespace-nowrap
                                                                {{ $action === 'start' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                                        {{ ucfirst($action) }} All
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>

                    <div x-show="open" x-collapse class="bg-gray-900 rounded-b">
                        <table class="w-full text-sm text-white">
                            <thead class="bg-gray-800 text-left">
                                <tr>
                                    <th class="p-3">Container Name</th>
                                    <th class="p-3">Container ID</th>
                                    <th class="p-3">Local IP</th>
                                    <th class="p-3">External IP</th>
                                    <th class="p-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-500">
                                @foreach ($service['containers'] as $container)
                                    <tr class="hover:bg-gray-700 transition">
                                        <td class="p-3">{{ $container['name'] }}</td>
                                        <td class="p-3 text-blue-400">
                                            <code>{{ Str::limit($container['id'], 12, '') }}</code>
                                        </td>
                                        <td class="p-3">{{ $container['loc_ip'] }}</td>
                                        <td class="p-3">{{ $container['ext_ip'] }}</td>
                                        <td class="p-3 text-center space-x-1">
                                            <form action="{{ route('user.containers.start', $container['id']) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <input type="hidden" name="open_service" value="{{ $service['name'] }}">
                                                <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded text-sm">
                                                    Start
                                                </button>
                                            </form>
                                            <form action="{{ route('user.containers.stop', $container['id']) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <input type="hidden" name="open_service" value="{{ $service['name'] }}">
                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm">
                                                    Stop
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400">Belum ada service dengan container untuk kamu.</div>
            @endforelse
        </div>
    </div>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
    </script>
</x-app-layout>
