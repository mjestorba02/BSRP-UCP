{{-- Houses --}}
@if($houses->isNotEmpty())
    @foreach ($houses as $house)
        @php
            $statusData = app('App\Http\Controllers\ProfileController')->getOwnershipStatus($house->timestamp, $user->vippackage);
            $zoneName = app('App\Http\Controllers\ProfileController')->getZoneName($house->pos_x, $house->pos_y, $house->pos_z);
            $houseType = app('App\Http\Controllers\ProfileController')->getHouseType($house->type);
        @endphp
        <div class="w-full min-w-[320px]">
            <div class="flex rounded-xl border border-gray-100/30 shadow overflow-hidden">
                <!-- Left: Image -->
                <div class="w-1/3">
                    <img 
                        src="https://files.prineside.com/gtasa_samp_model_id/white/1273_w.jpg" 
                        alt="House Image" 
                        class="w-full h-full object-cover"
                    >
                </div>

                <!-- Right: Info -->
                <div class="w-2/3 p-4">
                    <h3 class="text-md font-semibold text-gray-400 mb-1">
                        {{ $house->address ?? 'Unnamed House' }}
                    </h3>
                    <p class="text-sm text-white">ID: {{ $house->id }}</p>
                    <p class="text-sm text-white">Type: {{ $houseType }}</p>
                    <p class="text-sm text-white">Vault: {{ $house->cash }}</p>
                    <p class="text-sm text-white">Location: {{ $zoneName }}</p>
                    <p class="text-sm text-white">
                        Status: <span style="color: {{ $statusData['color'] }};">{{ $statusData['status'] }}</span>
                    </p>
                </div>
            </div>
        </div>
    @endforeach
@else
    <p class="text-gray-500 col-span-full">You don't have any house.</p>
@endif