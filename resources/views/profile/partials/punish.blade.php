<!-- Crimes -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14v1a4 4 0 01-4 4H9a4 4 0 01-4-4v-1" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a4 4 0 100-8 4 4 0 000 8z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Crimes</h4>
        <p class="text-lg text-white font-semibold">{{ $user->crimes ?? 0 }}</p>
    </div>
</div>

<!-- Arrested -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 21h14a2 2 0 002-2V9H3v10a2 2 0 002 2z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Arrested</h4>
        <p class="text-lg text-white font-semibold">{{ $user->arrested ?? 0 }}</p>
    </div>
</div>

<!-- Warnings -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Warnings</h4>
        <p class="text-lg text-white font-semibold">{{ $user->warnings ?? 0 }}</p>
    </div>
</div>

<!-- DM Warnings -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">DM Warnings</h4>
        <p class="text-lg text-white font-semibold">{{ $user->dmwarnings ?? 0 }}</p>
    </div>
</div>

<!-- Report Warns -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M9.75 9.75L5 5m0 0v5m0-5h5m9.5 9.5L19 19m0 0v-5m0 5h-5" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Report Warns</h4>
        <p class="text-lg text-white font-semibold">{{ $user->reportwarns ?? 0 }}</p>
    </div>
</div>

<!-- Weapon Restricted -->
<div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
    <div>
        <h4 class="text-sm text-gray-400">Weapon Restricted</h4>
        <p class="text-lg text-white font-semibold">
            {{ $user->weaponrestricted > 0 ? $user->weaponrestricted . ' hour' . ($user->weaponrestricted > 1 ? 's' : '') : 'No' }}
        </p>
    </div>
</div>
