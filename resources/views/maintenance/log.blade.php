<x-app-layout>
    <div class="p-6 text-white bg-gray-900 min-h-screen">
        <h1 class="text-3xl font-bold mb-4 flex items-center gap-2">
            📝 Log Container: <span class="text-blue-400">{{ $name ?? 'Unknown' }}</span>
        </h1>

        {{-- Status & Uptime --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="text-sm bg-gray-700 px-3 py-1 rounded-full flex items-center gap-2">
                <span class="w-2 h-2 rounded-full 
            {{ ($container['State'] ?? '') === 'running' ? 'bg-green-500' :
    (($container['State'] ?? '') === 'exited' ? 'bg-red-500' : 'bg-gray-500') }}">
                </span>
                Status:
                <span class="{{ ($container['State'] ?? '') === 'running' ? 'text-green-400' : 'text-red-400' }}">
                    {{ ucfirst($container['State'] ?? 'Unknown') }}
                </span>
            </div>

            @if (!empty($container['Uptime']))
                <div class="text-sm bg-gray-700 px-3 py-1 rounded-full">
                    Uptime: <span class="text-yellow-400">{{ $container['Uptime'] }}</span>
                </div>
            @endif
        </div>

        {{-- Log Output --}}
        <div class="bg-gray-800 rounded-lg shadow p-4 overflow-auto max-h-[600px] border border-blue-600">
            <pre class="text-sm font-mono text-green-200 whitespace-pre-wrap leading-relaxed">
{{ $log ?? 'Tidak ada log.' }}
            </pre>
        </div>

        {{-- Back Button --}}
        <a href="{{ route('maintenance.dashboard') }}"
            class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow">
            ⬅️ Kembali ke Dashboard
        </a>
    </div>
</x-app-layout>