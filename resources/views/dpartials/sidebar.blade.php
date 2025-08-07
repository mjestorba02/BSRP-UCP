<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-55 h-screen pt-20 transition-transform -translate-x-full sm:translate-x-0 bg-gradient-to-r from-black to-[#410000] shadow shadow-xl" aria-label="Sidebar">
    <div class="w-full h-full px-4 pb-4 overflow-y-auto bg-gradient-to-r from-black to-[#410000] shadow">
        <!-- Title -->
        <div class="flex justify-center items-center mb-6">
            <h1 class="text-[21px] font-extrabold text-white font-[Times_New_Roman] uppercase">Control Panel</h1>
        </div>

        <!-- Navigation Links -->
        <ul class="w-full space-y-2 text-[14px] font-medium text-white">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                class="group flex items-center p-1 rounded-lg transition hover:bg-[#410000]
                {{ request()->routeIs('dashboard') ? 'text-white' : '' }}">
                    <img src="{{ asset('svg/dashboard.svg') }}"
                        alt="Dashboard Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span
                        class="text-base font-medium
                        {{ request()->routeIs('dashboard') ? 'text-white' : 'text-[#c7c7c7]' }}
                        group-hover:text-white transition">
                        Dashboard
                    </span>
                </a>
            </li>

            <div class="mt-8 text-xs font-semibold uppercase text-gray-500 tracking-wider">Personal:</div>

            <!-- Profile -->
            <li>
                <a href="{{ route('profile') }}"
                class="group flex items-center p-1 rounded-lg transition hover:bg-[#410000]
                {{ request()->routeIs('profile') ? 'text-white' : '' }}">
                    <img src="{{ asset('svg/profile.svg') }}"
                        alt="Profile Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span
                        class="text-base font-medium
                        {{ request()->routeIs('profile') ? 'text-white' : 'text-[#c7c7c7]' }}
                        group-hover:text-white transition">
                        Profile
                    </span>
                </a>
            </li>

            <!-- Misc -->
            <li x-data="{ open: false }">
                <button @click="open = !open"
                    class="group flex items-center p-1 w-full rounded-lg transition hover:bg-[#410000] focus:outline-none">
                    <img src="{{ asset('svg/misc.svg') }}"
                        alt="Misc Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span class="text-base font-medium text-[#c7c7c7] group-hover:text-white transition">
                        Misc
                    </span>
                    <svg :class="{ 'rotate-180': open }" class="ml-auto w-4 h-4 transition-transform text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('roster') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Admin Roster
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('toys') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Clothing Items
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('misc.phonebook') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Phone Book
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('turfs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Turfs & Traphouse Info
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Gangs -->
            @if($user->gang > 0)
            <div class="mt-8 text-xs font-semibold uppercase text-gray-500 tracking-wider">Gangs:</div>

            <li x-data="{ open: false }">
                <button @click="open = !open"
                    class="group flex items-center p-1 w-full rounded-lg transition hover:bg-[#410000] focus:outline-none">
                    <img src="{{ asset('svg/settings.svg') }}"
                        alt="Gang Management Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span class="text-base font-medium text-[#c7c7c7] group-hover:text-white transition">
                        Gang
                    </span>
                    <svg :class="{ 'rotate-180': open }" class="ml-auto w-4 h-4 transition-transform text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('gang.manage') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Members
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gang.stats') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Stats
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gang.inventory') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Inventory
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gang.logs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Logs
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- Factions -->
            @if($user->faction > 0)
            <div class="mt-8 text-xs font-semibold uppercase text-gray-500 tracking-wider">Factions:</div>

            <li x-data="{ open: false }">
                <button @click="open = !open"
                    class="group flex items-center p-1 w-full rounded-lg transition hover:bg-[#410000] focus:outline-none">
                    <img src="{{ asset('svg/settings.svg') }}"
                        alt="Gang Management Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span class="text-base font-medium text-[#c7c7c7] group-hover:text-white transition">
                        Faction
                    </span>
                    <svg :class="{ 'rotate-180': open }" class="ml-auto w-4 h-4 transition-transform text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('faction.manage') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Members
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('faction.logs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Logs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('faction.lockerlogs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Locker Logs
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- Admins -->
            @if($user->adminlevel > 0)
            <div class="mt-8 text-xs font-semibold uppercase text-gray-500 tracking-wider">Admins:</div>

            <li>
                <a href="{{ route('admin.roster') }}"
                class="group flex items-center p-1 rounded-lg transition hover:bg-[#410000]">
                    <img src="{{ asset('svg/roster.svg') }}"
                        alt="Roster Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span class="text-base font-medium text-[#c7c7c7] group-hover:text-white transition">
                        Roster
                    </span>
                </a>
            </li>

            <!-- Logs -->
            <li x-data="{ open: false }">
                <button @click="open = !open"
                    class="group flex items-center p-1 w-full rounded-lg transition hover:bg-[#410000] focus:outline-none">
                    <img src="{{ asset('svg/logs.svg') }}"
                        alt="Logs Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span class="text-base font-medium text-[#c7c7c7] group-hover:text-white transition">
                        Logs
                    </span>
                    <svg :class="{ 'rotate-180': open }" class="ml-auto w-4 h-4 transition-transform text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                    @if($user->adminlevel == 7)
                    <li>
                        <a href="{{ route('admin.cmdlogs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Admin Command
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('admin.logs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Admin
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.cmdlogs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Commands
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.banlogs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Ban
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.lootlogs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Loot
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.quitlogs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Quit
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.killlogs') }}"
                            class="flex items-center px-2 py-1 rounded-md text-sm text-gray-300 hover:text-white hover:bg-[#410000] transition">
                            • Kill
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </div>
</aside>