{{-- Businesses --}}
@if($businesses->isNotEmpty())
    @foreach ($businesses as $biz)
        @php
            $statusData = app('App\Http\Controllers\ProfileController')->getOwnershipStatus($biz->timestamp, $user->vippackage);
        @endphp
        <div class="rounded-xl border border-gray-100/30 shadow p-4">
            <h3 class="text-md font-semibold text-gray-400 mb-1">
                {{ $biz->name ?? 'Unnamed Business' }}
            </h3>
            <p class="text-sm text-white">ID: {{ $biz->id }}</p>
            <p class="text-sm text-white">
                Type:
                {{ $businessTypeMap[$biz->type] ?? 'Unknown' }}
            </p>
            <p class="text-sm text-white">Earnings: ${{ number_format($biz->cash, 2) }}</p>
            <p class="text-sm text-white">
                Status: <span style="color: {{ $statusData['color'] }};">{{ $statusData['status'] }}</span>
            </p>
        </div>
    @endforeach
@else
    <p class="text-gray-500 col-span-full">You don't have any business.</p>
@endif