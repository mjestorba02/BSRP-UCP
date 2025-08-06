<div class="flex mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <!-- Control Panel -->
        <li class="inline-flex items-center">
            <a href="#" class="inline-flex items-center text-sm font-medium text-white hover:text-gray-300">
                <svg class="w-3 h-3 mr-2.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                Control Panel
            </a>
        </li>

        <!-- Miscellaneous -->
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="#" class="ml-1 text-sm font-medium text-white hover:text-gray-300 md:ml-2">Miscellaneous</a>
            </div>
        </li>

        <!-- Inventory -->
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Gang Inventory</span>
            </div>
        </li>
    </ol>
</div>

<h1 class="text-xl text-white font-bold mb-4">Gang Inventory</h1>
<div class="px-6 py-4 bg-gradient-to-r from-[#410000] via-black to-[#410000] rounded-xl shadow-inner">
    <h3 class="text-xl font-bold text-white mb-4">Items</h3>

    @if ($gang)
        @php
            $inventoryItems = [
                ['item' => 'Cash', 'value' => $gang->cash, 'objectid' => 1274],
                ['item' => 'Raw Materials', 'value' => $gang->materials, 'objectid' => 1575],
                ['item' => 'High-Grade Materials', 'value' => $gang->hmaterials, 'objectid' => 1577],
                ['item' => 'Pot', 'value' => $gang->pot, 'objectid' => 1578],
                ['item' => 'Crack', 'value' => $gang->crack, 'objectid' => 1579],
                ['item' => 'Meth', 'value' => $gang->meth, 'objectid' => 1580],
                ['item' => 'Kush Seeds', 'value' => $gang->kushseeds, 'objectid' => 1279],
                ['item' => 'Coca Seeds', 'value' => $gang->cocaseeds, 'objectid' => 1279],
                ['item' => 'Ephedra Seeds', 'value' => $gang->ephedraseeds, 'objectid' => 1279],
                ['item' => 'Pistol Clips', 'value' => $gang->pistolclip, 'objectid' => 19832],
                ['item' => 'SMG Clips', 'value' => $gang->smgclip, 'objectid' => 19832],
                ['item' => 'Shotgun Clips', 'value' => $gang->shotgunclip, 'objectid' => 19832],
                ['item' => 'Rifle Clips', 'value' => $gang->rifleclip, 'objectid' => 19832],
                ['item' => 'Sniper Clips', 'value' => $gang->sniperclip, 'objectid' => 19107],
            ];
        @endphp

        {{-- Inventory Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
            @foreach ($inventoryItems as $inv)
                @if ($inv['value'] != 0)
                    <div class="bg-neutral-900/60 w-52 rounded-lg border border-red-700 shadow-lg hover:shadow-xl transition duration-200 ease-in-out">
                        <!-- Image Section -->
                        <div class="h-32 rounded-t-lg overflow-hidden">
                            <img src="https://files.prineside.com/gtasa_samp_model_id/white/{{ $inv['objectid'] }}_w.jpg"
                                alt="{{ $inv['item'] }}"
                                class="w-full h-full object-cover" />
                        </div>

                        <!-- Info Section -->
                        <div class="p-4">
                            <p class="text-base text-white font-semibold mb-2 truncate">{{ $inv['item'] }}</p>
                            <p class="text-sm text-gray-300">Amount: <span class="text-white font-bold">{{ number_format($inv['value']) }}</span></p>
                            <p class="text-sm text-gray-400">Object ID: {{ $inv['objectid'] }}</p>
                        </div>
                    </div>

                @endif
            @endforeach
        </div>
    @else
        <p class="text-gray-400 italic">No inventory data available.</p>
    @endif
</div>

<div class="px-6 py-4 mt-8 bg-gradient-to-r from-[#410000] via-black to-[#410000] rounded-xl shadow-inner">
    <h3 class="text-xl font-bold text-white mb-4 border-b border-neutral-700 pb-2">Weapons</h3>

    @if ($gang)
        @php
            $hasWeapons = false;
            $weaponObjectMap = [
                22 => 346, 23 => 347, 24 => 348,
                25 => 349, 26 => 350, 27 => 351,
                28 => 352, 29 => 353, 30 => 355,
                31 => 356, 32 => 372, 33 => 357,
                34 => 358, 35 => 359, 36 => 360,
                37 => 361, 38 => 362, 16 => 342,
                17 => 343, 18 => 344, 39 => 363,
                41 => 365, 42 => 366, 43 => 367,
                44 => 368, 45 => 369, 46 => 371,
                10 => 331, 11 => 333, 12 => 334,
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
            @for ($i = 1; $i <= 50; $i++)
                @php
                    $weaponId = $gang->{'weapon_' . $i};
                    $weaponAmmo = $gang->{'weapona_' . $i};
                    $weaponDurability = $gang->{'weapond_' . $i};
                    $objectId = $weaponObjectMap[$weaponId] ?? null;
                @endphp

                @if ($weaponId && $objectId)
                    @php $hasWeapons = true; @endphp

                    <div class="bg-neutral-900/60 w-52 rounded-lg border border-red-700 shadow-lg hover:shadow-xl transition duration-200 ease-in-out">
                        <div class="h-32 rounded-t-lg overflow-hidden">
                            <img src="https://files.prineside.com/gtasa_samp_model_id/white/{{ $objectId }}_w.jpg"
                                alt="Weapon {{ $weaponId }}"
                                class="w-full h-full object-cover" />
                        </div>

                        <div class="p-4">
                            <p class="text-gray-400 text-xs mb-2">Slot {{ $i }}</p>
                            <p class="text-white text-sm font-semibold">Weapon ID: {{ $weaponId }}</p>
                            <p class="text-white text-sm">Ammo: <span class="text-gray-300">{{ $weaponAmmo }}</span></p>
                            <p class="text-white text-sm">Durability: <span class="text-gray-300">{{ $weaponDurability }}</span></p>
                        </div>
                    </div>
                @endif
            @endfor
        </div>

        @if (!$hasWeapons)
            <div class="text-white text-center mt-6 text-sm italic">
                No weapons found.
            </div>
        @endif
    @else
        <p class="text-gray-400 italic">No inventory data available.</p>
    @endif
</div>