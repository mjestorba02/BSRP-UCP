@if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 3000)" 
        x-show="show"
        class="fixed inset-0 flex items-center justify-center z-50"
    >
        <div class="text-green-500 bg-black/80 border border-green-700 p-4 rounded-lg shadow-lg text-center">
            {{ session('success') }}
        </div>
    </div>
@endif

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

<!-- Announcements & Updates Section -->
<div class="p-6">
    <div class="flex flex-col lg:flex-row gap-6 items-start">
        <!-- Announcements Panel -->
        <div class="flex-1 bg-gradient-to-r from-black via-[#410000] to-black rounded-2xl p-6 shadow-lg">
            <h3 class="text-white text-xl font-bold mb-4 flex items-center gap-2">
                📣 <span>Announcements</span>
            </h3>

            <ul>
                @forelse ($announcements as $announcement)
                    @php $isAdmin = $user->adminrank > 0; @endphp
                    <li class="p-5 mb-5 bg-gradient-to-r from-[#410000] via-black to-[#410000] rounded-xl shadow-2xl hover:shadow-gray-800/80 transition duration-300 transform hover:scale-[1.01]">
                        <div class="whitespace-pre-line text-sm text-white">
                            {!! $announcement->message !!}
                        </div>

                        @if ($announcement->image_url)
                            <div class="mt-4">
                                <img src="{{ $announcement->image_url }}" alt="Announcement Image" class="rounded-xl max-w-full border border-gray-600 shadow-md" />
                            </div>
                        @endif

                        <div class="text-xs text-gray-400 mt-3 italic flex justify-between items-center">
                            <span>{{ $announcement->created_at->diffForHumans() }}</span>

                            @if ($isAdmin)
                            <div x-data="{ showConfirm: false }">
                                <!-- Trigger Button -->
                                <button 
                                    @click="showConfirm = true" 
                                    class="text-gray-100 hover:text-gray-400 text-xs bg-black/20 px-3 py-1 rounded-md border border-gray-100">
                                    Delete
                                </button>

                                <!-- Modal -->
                                <div 
                                    x-show="showConfirm"
                                    x-transition
                                    class="fixed inset-0 flex items-center justify-center bg-black/10 backdrop-blur-xs z-50"
                                >
                                    <div class="bg-gradient-to-r from-black to-[#410000] p-6 rounded-lg shadow-lg max-w-sm w-full">
                                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Confirm Deletion</h2>
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                            Are you sure you want to delete this announcement?
                                        </p>

                                        <div class="mt-4 flex justify-end space-x-2">
                                            <!-- Cancel Button -->
                                            <button 
                                                @click="showConfirm = false"
                                                class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                                Cancel
                                            </button>

                                            <!-- Confirm Delete Form -->
                                            <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit" 
                                                    class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                                    Confirm
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="text-white italic">No announcements available.</li>
                @endforelse
            </ul>

            <p class="mt-6 text-xs text-gray-500 italic border-t border-gray-700 pt-4">
                Last updated: {{ now()->format('F d, Y') }}
            </p>
        </div>

        <!-- Updates Panel -->
        <div class="flex-1 bg-gradient-to-r from-black via-[#410000] to-black rounded-2xl p-6 shadow-lg">
            <h3 class="text-white text-xl font-bold mb-4 flex items-center gap-2">
                🛠️ <span>Patch & Updates</span>
            </h3>

            <ul>
                @forelse ($updates as $update)
                    @php $isAdmin = $user->adminrank > 0; @endphp
                    <li class="p-5 mb-5 bg-gradient-to-r from-[#410000] via-black to-[#410000] rounded-xl shadow-2xl hover:shadow-gray-800/80 transition duration-300 transform hover:scale-[1.01]">
                        <div class="whitespace-pre-line text-sm text-white">
                            {!! $update->updates !!}
                        </div>

                        @if ($update->image_url)
                            <div class="mt-4">
                                <img src="{{ $update->image_url }}" alt="Updates Image" class="rounded-xl max-w-full border border-gray-600 shadow-md" />
                            </div>
                        @endif

                        <div class="text-xs text-gray-400 mt-3 italic flex justify-between items-center">
                            <span>{{ $update->created_at->diffForHumans() }}</span>

                            @if ($user->adminrank > 0)
                            <div x-data="{ showConfirm: false }">
                                <!-- Trigger Button -->
                                <button 
                                    @click="showConfirm = true" 
                                    class="text-gray-100 hover:text-gray-400 text-xs bg-black/20 px-3 py-1 rounded-md border border-gray-100">
                                    Delete
                                </button>

                                <!-- Modal -->
                                <div 
                                    x-show="showConfirm"
                                    x-transition
                                    class="fixed inset-0 flex items-center justify-center bg-black/10 backdrop-blur-xs z-50"
                                >
                                    <div class="bg-gradient-to-r from-black to-[#410000] p-6 rounded-lg shadow-lg max-w-sm w-full">
                                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Confirm Deletion</h2>
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                            Are you sure you want to delete this updates?
                                        </p>

                                        <div class="mt-4 flex justify-end space-x-2">
                                            <!-- Cancel Button -->
                                            <button 
                                                @click="showConfirm = false"
                                                class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                                Cancel
                                            </button>

                                            <!-- Confirm Delete Form -->
                                            <form action="{{ route('updates.destroy', $update->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit" 
                                                    class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                                    Confirm
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="text-white italic">No Updates available.</li>
                @endforelse
            </ul>

            <p class="mt-6 text-xs text-gray-500 italic border-t border-gray-700 pt-4">
                Last updated: {{ now()->format('F d, Y') }}
            </p>
        </div>
    </div>
</div>