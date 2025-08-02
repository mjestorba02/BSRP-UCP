{{-- Houses --}}
@if($houses->isNotEmpty())
    @foreach ($houses as $house)
        @php
            $statusData = app('App\Http\Controllers\ProfileController')->getOwnershipStatus($house->timestamp, $user->vippackage);
        @endphp
        <div class="rounded-xl border border-gray-100/30 shadow p-4">
            <h3 class="text-md font-semibold text-gray-400 mb-1">
                {{ $house->address ?? 'Unnamed House' }}
            </h3>
            <p class="text-sm text-white">ID: {{ $house->id }}</p>
            <p class="text-sm text-white">Interior: {{ $house->interior }}</p>
            <p class="text-sm text-white">
                Status: <span style="color: {{ $statusData['color'] }};">{{ $statusData['status'] }}</span>
            </p>
        </div>
    @endforeach
@else
    <p class="text-gray-500 col-span-full">You don't have any house.</p>
@endif