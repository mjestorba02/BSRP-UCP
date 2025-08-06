<div class="mt-8">
    <h2 class="text-white text-xl font-semibold mb-4">Faction Logs</h2>

    <!-- Search Form -->
    <form method="GET" action="{{ route('faction.logs') }}" class="mb-4">
        <div class="flex gap-2 items-center">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search description..." 
                class="px-4 py-2 rounded-lg bg-neutral-800 text-white border border-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
            >
            <button 
                type="submit" 
                class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded-lg transition"
            >
                Search
            </button>
        </div>
    </form>

    @if ($logs->isEmpty())
        <div class="text-gray-300 italic text-sm">No logs found for this faction.</div>
    @else
        <div class="overflow-x-auto bg-neutral-900/60 rounded-lg border border-red-700 shadow">
            <table class="min-w-full text-sm text-left text-gray-300">
                <thead class="text-xs uppercase bg-neutral-800 text-red-500 border-b border-red-700">
                    <tr>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr class="border-b border-neutral-800 hover:bg-neutral-800/50">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-400">
                                {{ \Carbon\Carbon::parse($log->date)->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $log->description }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $logs->appends(['search' => request('search')])->links() }}
        </div>
    @endif
</div>