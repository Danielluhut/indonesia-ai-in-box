<x-app-layout>
    <div x-data="{ search: '', status: 'All' }"
        class="p-6 text-white min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">

        <h1 class="text-3xl font-bold mb-6">Daftar Service Seluruh User</h1>

        <!-- Filter/Search -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex flex-col md:flex-row items-center gap-4 w-full">
                <input type="text" placeholder="Cari service..." x-model="search"
                    class="px-4 py-2 rounded-md bg-gray-800 text-white border border-blue-500 w-full md:w-1/3" />

                <select x-model="status"
                    class="px-8 py-2 rounded-md bg-gray-800 text-white border border-blue-500">
                    <option value="All">Status: All</option>
                    <option value="Running">Running</option>
                    <option value="Stopped">Stopped</option>
                </select>

                <button @click="location.reload()"
                    class="px-4 py-2 bg-blue-600 rounded-md text-white hover:bg-blue-700">
                    Refresh
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-white border-collapse border border-blue-500">
                <thead class="bg-gray-800 text-left">
                    <tr>
                        <th class="p-3">Container Name</th>
                        <th class="p-3">Container ID</th>
                        <th class="p-3">User</th>
                        <th class="p-3">Machine</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-700 divide-y divide-blue-500">
                    @foreach ($containers as $container)
                        <tr
                            x-show="'{{ strtolower($container['container_name']) }}'.includes(search.toLowerCase()) && (status === 'All' || '{{ $container['status'] ?? 'Unknown' }}' === status)">
                            <td class="p-3">{{ $container['container_name'] }}</td>
                            <td class="p-3">{{ $container['id_container'] }}</td>
                            <td class="p-3">{{ $container['user'] ?? '-' }}</td>
                            <td class="p-3">{{ $container['machine'] ?? '-' }}</td>
                            <td class="p-3">
                                <span class="inline-flex items-center gap-2 justify-end">
                                    <span
                                        class="w-2 h-2 rounded-full {{ ($container['status'] ?? 'Unknown') === 'Running' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ $container['status'] ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="p-3 flex flex-wrap gap-2">
                                @foreach (['start', 'stop', 'restart', 'shutdown'] as $action)
                                    <form method="POST" action="{{ route('admin.container.action') }}">
                                        @csrf
                                        <input type="hidden" name="Action" value="{{ $action }}">
                                        <input type="hidden" name="id_container" value="{{ $container['id_container'] }}">
                                        <button class="px-3 py-1 rounded text-xs
                                            {{ $action === 'start' ? 'bg-green-600 hover:bg-green-700' :
                                               ($action === 'stop' ? 'bg-red-600 hover:bg-red-700' :
                                               ($action === 'restart' ? 'bg-yellow-600 hover:bg-yellow-700' :
                                               'bg-gray-600 hover:bg-gray-700')) }}">
                                            {{ ucfirst($action) }}
                                        </button>
                                    </form>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
