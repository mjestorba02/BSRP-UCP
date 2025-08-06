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
                <a href="#" class="ml-1 text-sm font-medium text-white hover:text-gray-300 md:ml-2">Gang</a>
            </div>
        </li>

        <!-- Toys -->
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Stats</span>
            </div>
        </li>
    </ol>
</div>

<div class="p-6">
    <div class="max-w-4xl mx-auto p-6 bg-gradient-to-r from-black to-[#410000] text-white rounded-xl shadow-lg space-y-8">
        <h3 class="text-lg font-semibold mb-4">Gang Overview</h3>
        <!-- Gang Stats Table -->
        <div class="bg-red-900/20 p-6 rounded-lg">
            <table class="min-w-full text-sm text-left">
                <tbody class="divide-y divide-red-900">
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-300">Name</td>
                        <td class="px-4 py-2 text-white">{{ $gang->name }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-300">MOTD</td>
                        <td class="px-4 py-2 text-white">{{ $gang->motd }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-300">Leader</td>
                        <td class="px-4 py-2 text-white">{{ $gang->leader ? str_replace('_', ' ', $gang->leader) : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-300">Level</td>
                        <td class="px-4 py-2 text-white">{{ $gang->level }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-300">Warnings</td>
                        <td class="px-4 py-2 text-white">{{ $gang->warnings }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-300">Strikes</td>
                        <td class="px-4 py-2 text-white">{{ $gang->strikes }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-300">Points</td>
                        <td class="px-4 py-2 text-white">{{ $gang->points }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-300">Turf Tokens</td>
                        <td class="px-4 py-2 text-white">{{ $gang->turftokens }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-semibold text-gray-300">Turfs Captured</td>
                        <td class="px-4 py-2 text-white">{{ $turfsCaptured }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>