<!-- Cash -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M3 10l9-7 9 7v11H3z" />
        <path d="M9 22V12h6v10" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Bank</h4>
        <p class="text-lg text-white font-semibold">{{ $user->bank ?? 0 }}</p>
    </div>
</div>

<!-- Level -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 14l9-5-9-5-9 5 9 5z" />
        <path d="M12 14l6.16-3.422A12.083 12.083 0 0112 22a12.083 12.083 0 01-6.16-11.422L12 14z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Level</h4>
        <p class="text-lg text-white font-semibold">{{ $user->level ?? 0 }}</p>
    </div>
</div>

<!-- DOB -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" 
            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 
                2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Date of Birth</h4>
        <p class="text-lg text-white font-semibold">
            {{ $user->birthmonth }}/{{ $user->birthday }}/{{ $user->birthyear }}
        </p>
    </div>
</div>

<!-- Health -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 21C12 21 5 13.5 5 8.5C5 5.46 7.46 3 10.5 3C11.88 3 13.16 3.66 14 4.68C14.84 3.66 16.12 3 17.5 3C20.54 3 23 5.46 23 8.5C23 13.5 16 21 16 21H12z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Health</h4>
        <p class="text-lg text-white font-semibold">{{ $user->health ?? 100 }}</p>
    </div>
</div>

<!-- Armor -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 2l8 4v6c0 5-3.5 9.74-8 10-4.5-.26-8-5-8-10V6l8-4z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Armor</h4>
        <p class="text-lg text-white font-semibold">{{ $user->armor ?? 0 }}</p>
    </div>
</div>

<!-- Phone Number -->
<div class="rounded-xl border border-white/20 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5l4-1 2 3-1 2c1.5 3.5 4.5 6.5 8 8l2-1 3 2-1 4c-7 0-13-6-13-13z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Phone Number</h4>
        <p class="text-lg text-white font-semibold">{{ $user->phonenumber }}</p>
    </div>
</div>


<!-- Radio Frequency -->
<div class="rounded-xl border border-white/20 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20v-6m0 0V4m0 10h4m-4 0H8" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Radio Frequency</h4>
        <p class="text-lg text-white font-semibold">{{ $user->channel }}</p>
    </div>
</div>


<!-- Playing Hours  -->
<div class="rounded-xl border border-white/20 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Playing Hours</h4>
        <p class="text-lg text-white font-semibold">{{ $user->hours }}</p>
    </div>
</div>

<!-- Paycheck  -->
<div class="rounded-xl border border-white/20 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2m0 0c1.1 0 2 .9 2 2s-.9 2-2 2m0-6v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Paycheck</h4>
        <p class="text-lg text-white font-semibold">{{ $user->paycheck }}</p>
    </div>
</div>

<!-- Faction  -->
<div class="rounded-xl border border-white/20 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6l9 4.5-9 9-9-9L12 6z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Faction</h4>
        <p class="text-lg text-white font-semibold">{{ $factionName }}</p>
    </div>
</div>


<!-- Faction Rank  -->
<div class="rounded-xl border border-white/20 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l-3 3h6l-3-3zM12 4l4 8H8l4-8z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Faction Rank</h4>
        <p class="text-lg text-white font-semibold">{{ $factionRank }}</p>
    </div>
</div>


<!-- Gang  -->
<div class="rounded-xl border border-white/20 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16M4 12l2-2m14 2l-2-2m-6 4v6" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Gang</h4>
        <p class="text-lg text-white font-semibold">{{ $gangName }}</p>
    </div>
</div>


<!-- Gang Rank  -->
<div class="rounded-xl border border-white/20 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M12 4a8 8 0 100 16 8 8 0 000-16z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Gang Rank</h4>
        <p class="text-lg text-white font-semibold">{{ $gangRank }}</p>
    </div>
</div>


<!-- Experience  -->
<div class="rounded-xl border border-white/20 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Experience</h4>
        <p class="text-lg text-white font-semibold">{{ $user->exp }} / {{ $user->level * 1000 }}</p>
    </div>
</div>