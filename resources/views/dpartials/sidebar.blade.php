<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-55 h-screen pt-20 transition-transform -translate-x-full sm:translate-x-0 bg-black shadow shadow-xl" aria-label="Sidebar">
    <div class="w-full h-full px-4 pb-4 overflow-y-auto bg-black shadow">
        <!-- Title -->
        <div class="flex justify-center items-center mb-6">
            <h1 class="text-[21px] font-extrabold text-white font-[Times_New_Roman] uppercase">Control Panel</h1>
        </div>

        <!-- Navigation Links -->
        <ul class="w-full space-y-2 text-[14px] font-medium text-white">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                class="group flex items-center p-1 rounded-lg transition hover:bg-gray-700
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
                class="group flex items-center p-1 rounded-lg transition hover:bg-gray-700
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
            <li>
                <a href="#"
                class="group flex items-center p-1 rounded-lg transition hover:bg-gray-700">
                    <img src="{{ asset('svg/misc.svg') }}"
                        alt="Misc Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span class="text-base font-medium text-[#c7c7c7] group-hover:text-white transition">
                        Misc
                    </span>
                </a>
            </li>

            <!-- Gangs -->
            <div class="mt-8 text-xs font-semibold uppercase text-gray-500 tracking-wider">Gangs:</div>

            <li>
                <a href="#"
                class="group flex items-center p-1 rounded-lg transition hover:bg-gray-700">
                    <img src="{{ asset('svg/info.svg') }}"
                        alt="Gang Info Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span class="text-base font-medium text-[#c7c7c7] group-hover:text-white transition">
                        Gang Info
                    </span>
                </a>
            </li>

            <li>
                <a href="#"
                class="group flex items-center p-1 rounded-lg transition hover:bg-gray-700">
                    <img src="{{ asset('svg/settings.svg') }}"
                        alt="Gang Management Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span class="text-base font-medium text-[#c7c7c7] group-hover:text-white transition">
                        Gang Management
                    </span>
                </a>
            </li>

            <!-- Admins -->
            <div class="mt-8 text-xs font-semibold uppercase text-gray-500 tracking-wider">Admins:</div>

            <li>
                <a href="#"
                class="group flex items-center p-1 rounded-lg transition hover:bg-gray-700">
                    <img src="{{ asset('svg/wrench.svg') }}"
                        alt="Commands Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span class="text-base font-medium text-[#c7c7c7] group-hover:text-white transition">
                        Commands
                    </span>
                </a>
            </li>

        </ul>
    </div>
</aside>