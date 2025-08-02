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

        <!-- Miscellaneous -->
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="#" class="ml-1 text-sm font-medium text-white hover:text-gray-300 md:ml-2">Miscellaneous</a>
            </div>
        </li>

        <!-- Phone Book -->
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Phone Book</span>
            </div>
        </li>
    </ol>
</div>

<div class="p-6">
    <h1 class="text-xl text-white font-bold mb-4">Phonebook Directorys</h1>

    @if($entries->isEmpty())
        <p class="text-white">The phonebook directory is currently empty.</p>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-700">
            <table class="min-w-full text-sm text-white bg-gray-800 border-collapse">
                <thead>
                    <tr class="bg-gray-700 text-left">
                        <th class="p-3 border border-gray-600">#</th>
                        <th class="p-3 border border-gray-600">Name</th>
                        <th class="p-3 border border-gray-600">Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $index => $entry)
                        <tr class="hover:bg-gray-600">
                            <td class="p-3 border border-gray-700">
                                {{ ($currentPage - 1) * $perPage + $index + 1 }}
                            </td>
                            <td class="p-3 border border-gray-700">{{ $entry->name }}</td>
                            <td class="p-3 border border-gray-700">{{ $entry->number }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between">
            @if($currentPage > 1)
                <a href="{{ route('misc.phonebook', ['page' => $currentPage - 1]) }}"
                   class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">« Go Back</a>
            @endif

            @if($total > $currentPage * $perPage)
                <a href="{{ route('misc.phonebook', ['page' => $currentPage + 1]) }}"
                   class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600 ml-auto">Next »</a>
            @endif
        </div>
    @endif
</div>