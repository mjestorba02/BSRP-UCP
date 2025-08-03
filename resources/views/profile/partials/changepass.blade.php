@if (session('status'))
    <div class="mb-4 text-green-400">
        {{ session('status') }}
    </div>
@endif

@if (session('success'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
               bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg z-50"
    >
        {{ session('success') }}
    </div>
@endif

<div class="flex justify-center mt-6" x-data="{ showSuccess: {{ session('success') ? 'true' : 'false' }} }" x-init="if(showSuccess){ setTimeout(() => showSuccess = false, 3000) }">
    
    {{-- ✅ Success Popup --}}
    <div 
        x-show="showSuccess" 
        x-transition 
        class="absolute inset-0 flex items-center justify-center z-50"
    >
        <div class="bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg text-center">
            {{ session('success') }}
        </div>
    </div>
    <form method="POST" action="{{ route('profile.changePassword') }}" class="space-y-4 w-full max-w-xl">
        @csrf

        <div>
            <label class="block text-sm text-gray-400 mb-1">Current Password</label>
            <input type="password" name="current_password" required
                class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('current_password')
                <div class="text-red-400 text-sm mt-1 text-center">{{ $message }}</div>
            @enderror
            <p class="text-sm text-gray-400 mt-4 mb-10 text-center">
                Forgot password? Submit a recovery request
                <a href="https://discord.gg/bloodlinestreetsrp" target="_blank" class="text-red-400 hover:underline">here</a>.
            </p>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">New Password</label>
            <input type="password" name="new_password" required
                class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('new_password')
                <div class="text-red-400 text-sm mt-1 text-center">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" required
                class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('new_password_confirmation')
                <div class="text-red-400 text-sm mt-1 text-center">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-red-600 hover:bg-red-900 transition rounded-lg py-2 text-white font-semibold mt-2">
            Update Password
        </button>
    </form>
</div>

