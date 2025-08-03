<!-- Main Content -->

<div class="flex mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="#" class="inline-flex items-center text-sm font-medium text-white hover:text-gray-300">
                <svg class="w-3 h-3 mr-2.5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                Control Panel
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="#" class="ml-1 text-sm font-medium text-white hover:text-gray-300 md:ml-2">Dashboard</a>
            </div>
        </li>
    </ol>
</div>

<div class="p-6">
    <!-- Full Width Grid: 2 Columns -->
    <div class="grid grid-cols-1 lg:flex gap-6">
        <!-- Left Column: Player Card -->
        <div class="flex flex-col gap-6 flex-1">
            <!-- Announcements -->
            <div class="bg-black p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-semibold mb-4 text-white">📢 Announcements</h3>
                <ul>
                    @foreach ($announcements as $announcement)
                        <li class="p-4 mb-4 bg-gray-700 shadow rounded">
                            <p class="text-white">{{ $announcement->message }}</p>
                            @if ($announcement->image_url)
                                <img src="{{ $announcement->image_url }}" alt="Announcement Image" class="mt-2 max-w-xs rounded" />
                            @endif
                            <small class="text-gray-400">{{ $announcement->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Patch & Updates Announcement -->
            <div class="bg-black rounded-xl p-6 shadow-md">
                <h3 class="text-white text-md font-semibold mb-4">📢 Patch & Updates</h3>
                
                <div class="bg-gray-400 rounded-md p-4 text-white shadow-inner">
                    <ul class="list-disc list-inside space-y-2 text-sm">
                        <li><span class="font-semibold">New Feature:</span> Added gang rank tracking system.</li>
                        <li><span class="font-semibold">Fix:</span> Resolved crash when updating gang colors.</li>
                        <li><span class="font-semibold">Change:</span> Armor cap increased from 50 to 100.</li>
                        <li><span class="font-semibold">Improvement:</span> Optimized loading speed for user dashboard.</li>
                    </ul>
                </div>

                <p class="mt-4 text-sm text-gray-400 italic">Last updated: July 31, 2025</p>
            </div>
        </div>
        <!-- Right Column: Player Information -->
        <div class="bg-black rounded-xl p-6 shadow-md text-gray-300 h-full flex-1 flex flex-col">
            <!-- Avatar, Name, Badges -->
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/faces/' . $user->skin . '.png') }}" alt="Avatar" class="w-25 h-25 rounded-[10%]">
                <div>
                    <h3 class="text-xl font-bold text-white">{{ str_replace('_', ' ', $user->username ?? 'Noob Dude') }}</h3>
                    <div class="flex gap-2 mt-2">
                        @php
                            $donatorRank = $user->donator_rank;
                            $bgClass = match($donatorRank) {
                                'Bronze' => 'bg-yellow-800 text-white',
                                'Silver' => 'bg-gray-500 text-white',
                                'Gold' => 'bg-yellow-400 text-white',
                                'Platinum' => 'bg-cyan-400 text-white',
                                default => ''
                            };
                        @endphp

                        @if ($donatorRank !== 'None')
                            <span class="{{ $bgClass }} text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $donatorRank }} Donator
                            </span>
                        @endif


                        @if($user->admin_rank !== 'None')
                            <span class="bg-pink-600 text-gray-300 text-xs px-2 py-1 rounded-full">{{ $user->admin_rank }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Health & Armor Bars -->
            <div class="mt-6">
                <label class="text-sm text-white">Health</label>
                <div class="w-full bg-gray-700 rounded-full h-2 mt-1">
                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $user->health ?? 100 }}%"></div>
                </div>

                <label class="text-sm text-white mt-4 block">Armor</label>
                <div class="w-full bg-gray-700 rounded-full h-2 mt-1">
                    <div class="bg-blue-400 h-2 rounded-full" style="width: {{ $user->armor ?? 0 }}%"></div>
                </div>
            </div>

            <!-- Divider -->
            <hr class="border-gray-700 my-6">

            <!-- Info Cards Inside -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm text-white">Total Wealth</h4>
                    <p class="text-xl font-semibold mt-1">${{ number_format($wealth) }}</p>
                    <p class="text-sm text-gray-400 mt-1">
                        Bank: ${{ number_format($user->bank) }} | 
                        Cash: ${{ number_format($user->cash) }} | 
                        Card: ${{ number_format($cardTotal) }}
                    </p>
                </div>

                <div>
                    <h4 class="text-sm text-white">Level</h4>
                    <p class="text-xl font-semibold mt-1">Level {{ $user->level }}</p>
                </div>
                <div>
                    <h4 class="text-sm text-white">Hours Played</h4>
                    <p class="text-xl font-semibold mt-1">{{ $user->hours }} hrs</p>
                </div>
                <div>
                    <h4 class="text-sm text-white">Age</h4>
                    <p class="text-xl font-semibold mt-1">{{ $user->age }} yrs</p>
                </div>
                <div>
                    <h4 class="text-sm text-white">Phone Number</h4>
                    <p class="text-xl font-semibold mt-1">{{ $user->phonenumber }}</p>
                </div>
                <div>
                    <h4 class="text-sm text-white">Last Login</h4>
                    <p class="text-xl font-semibold mt-1">{{ $user->lastlogin }}</p>
                </div>
                <div>
                    <h4 class="text-sm text-white">Gang</h4>
                    @php
                        $gangName = $gangs->firstWhere('id', $user->gang)?->name ?? 'None';
                    @endphp
                    <p class="text-lg font-bold mt-1">{{ $gangName }}</p>
                </div>
                <div>
                    <h4 class="text-sm text-white">Rank</h4>
                    @php
                        $gangRank = $gangranks
                            ->first(fn($r) => $r->id == $user->gang && $r->rank == $user->gangrank)?->name ?? 'None';
                    @endphp
                    <p class="text-lg font-bold mt-1">{{ $gangRank }}</p>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    setInterval(() => {
        fetch('/api/get-latest-announcement')
            .then(res => res.json())
            .then(data => {
                document.getElementById('announcement-text').innerText = data.announcement;
            });
    }, 30000);
</script>
