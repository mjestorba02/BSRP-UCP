@forelse ($vehicles as $vehicle)
    @php
        $vehname = app('App\Http\Controllers\ProfileController')->getVehicleName($vehicle->modelid);
    @endphp
    <div class="rounded-xl border border-gray-100/30 p-4 shadow mb-4 bg-black/20">
        <div class="mb-4">
            <h3 class="text-md font-semibold text-white mb-1">
                {{ $vehname }}
            </h3>
            <p class="text-sm text-gray-300">Model ID: {{ $vehicle->modelid }}</p>
            <p class="text-sm text-gray-300">Plate: {{ $vehicle->plate }}</p>
            <p class="text-sm text-gray-300">Health: {{ $vehicle->health }}</p>
        </div>
        <div class="flex justify-center">
            <img src="{{ asset('images/vehicles2/' . $vehicle->modelid . '.png') }}"
                 alt="Model {{ $vehicle->modelid }}"
                 class="w-32 h-32 object-contain rounded-lg bg-black/30 border border-gray-500" />
        </div>
    </div>
@empty
    <p class="text-white">No vehicles owned.</p>
@endforelse