<!-- Cash -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2m0-4v8m0 0c1.1 0 2-.9 2-2s-.9-2-2-2"></path>
        <circle cx="12" cy="12" r="10" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Cash</h4>
        <p class="text-lg text-white font-semibold">{{ $user->cash ?? 0 }}</p>
    </div>
</div>

<!-- Bank -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M3 10l9-6 9 6v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-8z" />
        <path d="M9 22V12h6v10" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Bank</h4>
        <p class="text-lg text-white font-semibold">{{ $user->bank ?? 0 }}</p>
    </div>
</div>

<!-- Level -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M8 17l4 4 4-4m-4-5v9" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Level</h4>
        <p class="text-lg text-white font-semibold">{{ $user->level ?? 0 }}</p>
    </div>
</div>

<!-- Date of Birth -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Date of Birth</h4>
        <p class="text-lg text-white font-semibold">{{ $user->dob ?? 'N/A' }}</p>
    </div>
</div>

<!-- Health -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Health</h4>
        <p class="text-lg text-white font-semibold">{{ $user->health ?? 0 }}</p>
    </div>
</div>

<!-- Armor -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 22c4.97-1.2 8-5.2 8-10V5l-8-3-8 3v7c0 4.8 3 8.8 8 10z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Armor</h4>
        <p class="text-lg text-white font-semibold">{{ $user->armor ?? 0 }}</p>
    </div>
</div>

<!-- Phone Number -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.1 4.18 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.72c.14 1.11.47 2.17.99 3.17a2 2 0 0 1-.45 2.18L8.09 10.91a16 16 0 0 0 6 6l1.84-1.84a2 2 0 0 1 2.18-.45c1 .52 2.06.85 3.17.99a2 2 0 0 1 1.72 2z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Phone Number</h4>
        <p class="text-lg text-white font-semibold">{{ $user->phone_number ?? 'N/A' }}</p>
    </div>
</div>

<!-- Radio Frequency -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 4v16m8-8H4" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Radio Frequency</h4>
        <p class="text-lg text-white font-semibold">{{ $user->radio_freq ?? 'N/A' }}</p>
    </div>
</div>

<!-- Playing Hours -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 8v5l3 3M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Playing Hours</h4>
        <p class="text-lg text-white font-semibold">{{ $user->playing_hours ?? 0 }}</p>
    </div>
</div>

<!-- Paycheck -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M4 6h16M4 10h16M4 14h10m-5 4h5" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Paycheck</h4>
        <p class="text-lg text-white font-semibold">{{ $user->paycheck ?? 0 }}</p>
    </div>
</div>

<!-- Faction -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m10-11l2 2m-2-2v10a1 1 0 0 1-1 1h-3" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Faction</h4>
        @php
            $factionName = $factions->firstWhere('id', $user->faction)?->name ?? 'None';
        @endphp
        <p class="text-lg text-white font-semibold">{{ $factionName }}</p>
    </div>
</div>

<!-- Faction Rank -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M6 18L18 6M6 6l12 12" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Faction Rank</h4>
        @php
            $factionRank = $factionranks
                ->first(fn($r) => $r->id == $user->faction && $r->rank == $user->factionrank)?->name ?? 'None';
        @endphp
        <p class="text-lg text-white font-semibold">{{ $factionRank }}</p>
    </div>
</div>

<!-- Gang -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" />
        <path d="M12 6v6l4 2" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Gang</h4>
        @php
            $gangName = $gangs->firstWhere('id', $user->gang)?->name ?? 'None';
        @endphp
        <p class="text-lg text-white font-semibold">{{ $gangName }}</p>
    </div>
</div>

<!-- Gang Rank -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M3 3h18v18H3z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Gang Rank</h4>
        @php
            $gangRank = $gangranks
                ->first(fn($r) => $r->id == $user->gang && $r->rank == $user->gangrank)?->name ?? 'None';
        @endphp
        <p class="text-lg text-white font-semibold">{{ $gangRank }}</p>
    </div>
</div>

<!-- Experience -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 2l3 7h7l-5.5 4.5L18 22l-6-4-6 4 1.5-8.5L2 9h7z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Experience</h4>
        <p class="text-lg text-white font-semibold">{{ $user->exp ?? 0 }}</p>
    </div>
</div>
