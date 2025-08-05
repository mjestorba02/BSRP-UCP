{{-- Businesses --}}
@if($businesses->isNotEmpty())
    @foreach ($businesses as $biz)
        @php
            $statusData = app('App\Http\Controllers\ProfileController')->getOwnershipStatus($biz->timestamp, $user->vippackage);
            $zoneName = app('App\Http\Controllers\ProfileController')->getZoneName($biz->pos_x, $biz->pos_y, $biz->pos_z);
            $bizType = app('App\Http\Controllers\ProfileController')->getBizObject($biz->type);
        @endphp

        <div class="w-full min-w-[320px]">
            <div class="flex rounded-xl border border-gray-100/30 shadow overflow-hidden">
                <!-- Left: Image (you can use a dynamic image if available) -->
                <div class="w-1/3">
                    <img 
                        src="https://files.prineside.com/gtasa_samp_model_id/white/{{ $bizType['object'] }}_w.jpg" 
                        alt="Business Image" 
                        class="w-full h-full object-cover"
                    >
                </div>

                <!-- Right: Info -->
                <div class="w-2/3 p-4">
                    <h3 class="text-md font-semibold text-gray-400 mb-1">
                        {{ $biz->name ?? 'Unnamed Business' }}
                    </h3>
                    <p class="text-sm text-white">ID: {{ $biz->id }}</p>
                    <p class="text-sm text-white">Type: {{ $bizType['name'] }}</p>
                    <p class="text-sm text-white">Earnings: ${{ number_format($biz->cash, 2) }}</p>
                    <p class="text-sm text-white">Location: {{ $zoneName }}</p>
                    <p class="text-sm text-white">
                        Status: <span style="color: {{ $statusData['color'] }};">{{ $statusData['status'] }}</span>
                    </p>
                </div>
            </div>
        </div>
    @endforeach
@else
    <p class="text-gray-500 col-span-full">You don't have any business.</p>
@endif