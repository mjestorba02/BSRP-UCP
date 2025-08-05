@forelse ($vehicles as $vehicle)
    @php
        $vehname = app('App\Http\Controllers\ProfileController')->getVehicleName($vehicle->modelid);
        $zoneName = app('App\Http\Controllers\ProfileController')->getZoneName($vehicle->pos_x, $vehicle->pos_y, $vehicle->pos_z);
    @endphp
    <div class="rounded-xl border border-gray-100/30 shadow mb-4 bg-black/20 overflow-hidden">
        <!-- Image on top -->
        <div class="w-full h-48 bg-black/30 border-b border-gray-500">
            <img src="{{ asset('images/vehicles2/' . $vehicle->modelid . '.png') }}"
                 alt="Model {{ $vehicle->modelid }}"
                 class="w-full h-full object-contain p-4" />
        </div>
        
        <!-- Info below -->
        <div class="p-4 text-left">
            <h3 class="text-md font-semibold text-white mb-1">{{ $vehname }}</h3>
            <p class="text-sm text-gray-300">Plate: <span class="text-gray-400">{{ $vehicle->plate }}</span></p>
            <p class="text-sm text-gray-300">Ticket: <span class="text-gray-400">{{ $vehicle->tickets }}</span></p>

            @php
                $healthPercent = min(100, ($vehicle->health / 1000) * 100);
                $fuelPercent = min(100, $vehicle->fuel); // assuming fuel is already a percent
            @endphp

            <!-- Health -->
            <div class="flex items-center gap-2 mb-2">
                <p class="text-sm text-gray-300 whitespace-nowrap">Health:</p>
                <div class="flex-1 bg-gray-700 rounded-full h-4 relative overflow-hidden">
                    <div class="bg-red-500 h-4 rounded-full" style="width: {{ $healthPercent }}%;"></div>
                    <span class="absolute right-2 text-xs text-white top-0 h-4 leading-4">{{ number_format($vehicle->health, 1) }}</span>
                </div>
            </div>

            <!-- Fuel -->
            <div class="flex items-center gap-2">
                <p class="text-sm text-gray-300 whitespace-nowrap">Fuel:</p>
                <div class="flex-1 bg-gray-700 rounded-full h-4 relative overflow-hidden">
                    <div class="bg-red-500 h-4 rounded-full" style="width: {{ $fuelPercent }}%;"></div>
                    <span class="absolute right-2 text-xs text-white top-0 h-4 leading-4">{{ $fuelPercent }}%</span>
                </div>
            </div>
            
            <p class="text-sm text-gray-300">Impounded: <span class="text-gray-400">{{ $vehicle->impounded ? 'Yes' : 'No' }}</span></p>
            <p class="text-sm text-gray-300">Location: <span class="text-gray-400">{{ $zoneName }}</span></p>
        </div>
    </div>
@empty
    <div class="rounded-xl border border-gray-100/30 p-8 shadow mb-4 bg-black/20 flex justify-center items-center h-40">
        <p class="text-white text-center">No vehicles owned.</p>
    </div>
@endforelse