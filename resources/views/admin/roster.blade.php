<div class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="#" class="inline-flex items-center text-sm font-medium text-white hover:text-gray-300">
                <svg class="w-4 h-4 mr-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                Control Panel
            </a>
        </li>

        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-white md:ml-2">Admin Roster</span>
            </div>
        </li>
    </ol>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach ($admins as $admin)
        @php
            $rankColors = [
                1 => 'bg-gray-600',
                2 => 'bg-blue-600',
                3 => 'bg-green-600',
                4 => 'bg-yellow-600',
                5 => 'bg-orange-600',
                6 => 'bg-purple-700',
                7 => 'bg-red-600',
            ];

            $rankNames = [
                1 => 'Trial Admin',
                2 => 'Junior Admin',
                3 => 'General Admin',
                4 => 'Senior Admin',
                5 => 'Head Admin',
                6 => 'Executive Admin',
                7 => 'Management',
            ];

            $colorClass = $rankColors[$admin->adminlevel] ?? 'bg-gray-500';
            $rankName = $rankNames[$admin->adminlevel] ?? 'None';
        @endphp
        <div class="bg-white/5 border border-red-700/50 backdrop-blur-md rounded-2xl shadow-lg overflow-hidden p-6 hover:scale-[1.02] transition-transform duration-200 ease-in-out">
            <div class="flex items-start gap-5">
                <!-- Larger Skin Image -->
                <div class="w-24 h-28 rounded-xl overflow-hidden shadow-lg border border-white/10">
                    <img src="{{ asset('images/skins/' . $admin->skin . '.png') }}" alt="Skin Image" class="w-full h-full object-cover">
                </div>

                <!-- Admin Info -->
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-white">{{ str_replace('_', ' ', $admin->adminname) }}</h2>
                    <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold text-red-300 {{ $colorClass }} rounded-full">
                        {{ $rankName }}
                    </span>

                    <div class="mt-2 space-y-1">
                        <p class="text-xs text-gray-400">Divisions: <span class="text-gray-200">{{ $admin->division }}</span></p>
                        <p class="text-xs text-gray-400">Monthly Reports: <span class="text-gray-200">{{ $admin->mreports }}</span></p>
                        <p class="text-xs text-gray-400">Total Reports: <span class="text-gray-200">{{ $admin->reports }}</span></p>
                        <p class="text-xs text-gray-400">Last Login: <span class="text-gray-200">{{ $admin->lastlogin }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>