@if (session('success'))
    <div class="bg-green-700 text-white text-sm rounded px-4 py-2 mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-700 text-white text-sm rounded px-4 py-2 mb-4">
        {{ session('error') }}
    </div>
@endif

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
                <a href="#" class="ml-1 text-sm font-medium text-white hover:text-gray-300 md:ml-2">Faction</a>
            </div>
        </li>

        <!-- Toys -->
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Members</span>
            </div>
        </li>
    </ol>
</div>

<div class="p-6">
    <h1 class="text-xl text-white font-bold mb-4">Faction Members</h1>
    <div class="max-x-full mx-auto p-6 bg-gradient-to-r from-black to-[#410000] text-white rounded-xl shadow-lg space-y-8">

        @php
            $members_count = app('App\Http\Controllers\FactionController')->countFactionMembers($faction->id);
        @endphp

        <!-- Faction Info -->
        <div class="bg-red-900/20 p-4 rounded-lg space-y-2">
            <h1 class="text-xl text-white font-bold"></strong> {{ $faction->name }}</h1>
            <p class="text-gray-400"><strong>Members:</strong> <span class="text-white">{{ $members_count }}</span></p>
        </div>

        <!-- Members List -->
        <div class="bg-red-900/20 p-6 rounded-lg">
            <h3 class="text-lg font-semibold mb-4">Current Members</h3>
            <div class="overflow-x-auto rounded-lg border border-red-900">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-red-900 text-gray-300 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Profile</th>
                            <th class="px-4 py-3 text-center">Rank</th>
                            <th class="px-4 py-3 text-center">Last Login</th>
                            @php
                                $highestRank = $factionranks->where('id', $user->faction)->max('rank');
                            @endphp

                            @if ($user->factionrank == $highestRank)
                            <th class="px-4 py-3 text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-900">
                        @forelse($members as $member)
                            @php
                                $factionRank = $factionranks
                                    ->first(fn($r) => $r->id == $member->faction && $r->rank == $member->factionrank)?->name ?? 'None';
                            @endphp
                            <tr class="hover:bg-red-900 transition duration-150">
                                <td class="px-4 py-3 flex items-center space-x-3">
                                    <img class="w-9 h-9 rounded-full object-cover border border-gray-600"
                                        src="{{ asset('images/faces/' . $member->skin . '.png') }}"
                                        alt="Profile Photo">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium text-white">{{ str_replace('_', ' ', $member->username) }} [{{ $member->badgenumber }}]</span>
                                        @if ($member->lockerl)
                                            <span class="text-xs bg-yellow-500 text-black px-2 py-0.5 rounded-full">
                                                Locker Leader
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $highestRank = $factionranks->where('id', $user->faction)->max('rank');
                                    @endphp

                                    @if ($user->factionrank == $highestRank && $member->uid !== $user->uid)
                                        <form action="{{ route('faction.updateRank', $member->uid) }}" method="POST">
                                            @csrf
                                            <select name="factionrank" onchange="this.form.submit()" class="bg-red-800 text-white text-sm rounded p-1">
                                                @foreach($factionranks->where('id', $member->faction) as $rank)
                                                    <option value="{{ $rank->rank }}" @selected($rank->rank == $member->factionrank)>
                                                        {{ $rank->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    @else
                                        {{ $factionRank }} ({{ $member->factionrank }})
                                    @endif

                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $member->lastlogin ? \Carbon\Carbon::parse($member->lastlogin)->diffForHumans() : 'Never' }}
                                </td>
                                @php
                                    $highestRank = $factionranks->where('id', $user->faction)->max('rank');
                                @endphp

                                @if ($user->factionrank == $highestRank)
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-col space-y-1 items-center justify-center">
                                        <div x-data="{ confirmKick: false }" class="relative">
                                            <!-- Kick Button (Trigger Modal) -->
                                            <button 
                                                type="button"
                                                @click="confirmKick = true"
                                                class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1.5 rounded transition"
                                            >
                                                Kick
                                            </button>

                                            <!-- Kick Confirmation Modal -->
                                            <div 
                                                x-show="confirmKick"
                                                x-transition.opacity
                                                x-cloak
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/10 backdrop-blur-sm"
                                            >
                                                <!-- Modal Dialog -->
                                                <div 
                                                    @click.away="confirmKick = false"
                                                    class="bg-gradient-to-r from-black to-[#410000] rounded-xl shadow-xl p-6 w-full max-w-md mx-4 text-center relative"
                                                >
                                                    <h2 class="text-lg font-semibold text-white mb-4">Kick Member?</h2>
                                                    <p class="text-sm text-gray-200 mb-6">
                                                        Are you sure you want to remove this member from your faction?
                                                    </p>

                                                    <div class="flex justify-center gap-4">
                                                        <!-- Cancel Button -->
                                                        <button 
                                                            @click="confirmKick = false"
                                                            class="px-4 py-2 text-sm text-gray-700 bg-gray-200 hover:bg-gray-300 rounded transition"
                                                        >
                                                            Cancel
                                                        </button>

                                                        <!-- Confirm Button (Form Submission) -->
                                                        <form 
                                                            action="{{ route('faction.kick', $member->uid) }}" 
                                                            method="POST"
                                                        >
                                                            @csrf
                                                            @method('DELETE')
                                                            <button 
                                                                type="submit"
                                                                class="px-4 py-2 text-sm text-white bg-red-600 hover:bg-red-700 rounded transition"
                                                            >
                                                                Confirm
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Locker Leader Toggle --}}
                                        @if ($user->factionrank == $highestRank && $member->uid !== $user->uid)
                                            @if (!$member->lockerl)
                                                <form action="{{ route('faction.giveLocker', $member->uid) }}" method="POST">
                                                    @csrf
                                                    <button class="bg-yellow-500 hover:bg-yellow-600 text-black text-xs px-3 py-1.5 rounded transition">
                                                        Give Locker
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('faction.revokeLocker', $member->uid) }}" method="POST">
                                                    @csrf
                                                    <button class="bg-gray-500 hover:bg-gray-600 text-white text-xs px-3 py-1.5 rounded transition">
                                                        Revoke Locker
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-400">No members found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>