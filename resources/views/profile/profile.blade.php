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
                <a href="#" class="ml-1 text-sm font-medium text-white hover:text-gray-300 md:ml-2">Profile</a>
            </div>
        </li>
    </ol>
</div>

<div class="w-full h-[90%] mx-auto">
    <div class="w-full bg-black text-white rounded-lg shadow-lg flex">
        {{-- Left: Profile Image --}}
        <div class="w-1/4 relative">
            <img src="{{ asset('images/skinsv2/' . $user->skin . '.jpg') }}" 
                alt="Player Image" 
                class="w-full h-full object-cover shadow-lg z-0">

            {{-- Blurred overlay with name and ranks at bottom, aligned left --}}
            <div class="absolute bottom-0 left-0 w-full flex items-center h-16 backdrop-blur-sm bg-white/10 text-white px-4 z-10">
                <div class="flex flex-col justify-center">
                    <h1 class="text-lg font-semibold">{{ str_replace('_', ' ', $user->username ?? 'Noob Dude') }}</h1>
                    <div class="flex gap-2 mt-1">
                        @if($user->admin_rank !== 'None')
                            <span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">
                                {{ $user->admin_rank }}
                            </span>
                        @endif

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
                            <span class="{{ $bgClass }} text-xs px-2 py-0.5 rounded-full font-semibold">
                                {{ $donatorRank }} Donator
                            </span>
                        @endif

                        @if($user->customtitle !== '0')
                            @php
                                $color = $user->customcolor & 0xFFFFFFFF;
                                $rgb = ($color >> 8) & 0xFFFFFF;
                                $r = ($rgb >> 16) & 0xFF;
                                $g = ($rgb >> 8) & 0xFF;
                                $b = $rgb & 0xFF;

                                $hexColor = sprintf("#%06X", $rgb);

                                // Calculate brightness (luminance)
                                $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;

                                // Choose text color based on brightness
                                $textColorClass = $brightness > 150 ? 'text-black' : 'text-white';
                            @endphp
                            <span class="{{ $textColorClass }} text-xs px-2 py-0.5 rounded-full" style="background-color: {{ $hexColor }}">
                                {{ $user->customtitle }}
                            </span>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Content Section --}}
        <div class="w-3/4 pr-6" x-data="{ tab: 'stats' }">

            {{-- Buttons --}}
            <div 
                x-data="{
                    tab: localStorage.getItem('selectedTab') || 'stats',
                    setTab(value) {
                        this.tab = value;
                        localStorage.setItem('selectedTab', value);
                    }
                }"
                class="flex flex-col"
            >
                <div class="flex mb-4 divide-x divide-white border border-gray-100/30 rounded overflow-hidden">
                    <button
                        @click="setTab('stats')"
                        :class="tab === 'stats' ? 'bg-black' : 'bg-neutral-900'"
                        class="flex-1 px-4 py-2 font-semibold text-white hover:bg-black"
                    >
                        Stats
                    </button>
                    <button
                        @click="setTab('inventory')"
                        :class="tab === 'inventory' ? 'bg-black' : 'bg-neutral-900'"
                        class="flex-1 px-4 py-2 font-semibold text-white hover:bg-black"
                    >
                        Inventory
                    </button>
                    <button
                        @click="setTab('password')"
                        :class="tab === 'password' ? 'bg-black' : 'bg-neutral-900'"
                        class="flex-1 px-4 py-2 font-semibold text-white hover:bg-black"
                    >
                        Change Password
                    </button>
                    <button
                        @click="setTab('punish')"
                        :class="tab === 'punish' ? 'bg-black' : 'bg-neutral-900'"
                        class="flex-1 px-4 py-2 font-semibold text-white hover:bg-black"
                    >
                        Punish Records
                    </button>
                </div>

                <h2 x-show="tab === 'stats'" class="text-xl font-bold pl-6">Account Statistics</h2>
                <h2 x-show="tab === 'inventory'" class="text-xl font-bold pl-6">Account Inventory</h2>
                <h2 x-show="tab === 'password'" class="text-xl font-bold pl-6">Change Password</h2>

                {{-- PHP content --}}
                @php
                    $factionName = $factions->firstWhere('id', $user->faction_id)->name ?? 'None';
                    $factionRank = $factionranks->first(fn($r) => $r->id == $user->faction && $r->rank == $user->factionrank)?->name ?? 'None';

                    $gangName = $gangs->firstWhere('id', $user->gang)?->name ?? 'None';
                    $gangRank = $gangranks->first(fn($r) => $r->id == $user->gang && $r->rank == $user->gangrank)?->name ?? 'None';
                @endphp

                <div class="p-4">
                    {{-- Stats --}}
                    <div x-show="tab === 'stats'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                        @include('profile.partials.stats')
                    </div>

                    {{-- Inventory --}}
                    <div x-show="tab === 'inventory'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                        @include('profile.partials.inventory')
                    </div>

                    {{-- Change Password --}}
                    <div x-show="tab === 'password'">
                        @include('profile.partials.changepass')
                    </div>

                    {{-- Punish Records --}}
                    <div x-show="tab === 'punish'">
                        <h2 class="text-xl font-bold mb-2">Punish Records</h2>
                        <p class="text-sm text-gray-300">Punish records list goes here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
