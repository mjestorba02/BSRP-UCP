<div class="flex mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <!-- Control Panel -->
        <li class="inline-flex items-center">
            <a href="#" class="inline-flex items-center text-sm font-medium text-white hover:text-gray-300">
                <svg class="w-3 h-3 mr-2.5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                Control Panel
            </a>
        </li>

        <!-- Admin -->
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="#" class="ml-1 text-sm font-medium text-white hover:text-gray-300 md:ml-2">Admin</a>
            </div>
        </li>

        <!-- Quit Logs -->
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Quit Logs</span>
            </div>
        </li>
    </ol>
</div>

<div class="mt-8">
    <h2 class="text-white text-xl font-semibold mb-4">Quit Logs</h2>

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.quitlogs') }}" class="mb-4">
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
        <div class="text-gray-300 italic text-sm">No quit logs found.</div>
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
                            <td class="px-6 py-4 whitespace-nowrap text-gray-200">
                                {{ \Carbon\Carbon::parse($log->date)->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-white">
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