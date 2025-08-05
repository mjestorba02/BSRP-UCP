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
                <a href="#" class="ml-1 text-sm font-medium text-white hover:text-gray-300 md:ml-2">Miscellaneous</a>
            </div>
        </li>

        <!-- Toys -->
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Clothing Items</span>
            </div>
        </li>
    </ol>
</div>

<div class="px-6 py-4 bg-gradient-to-r from-[#410000] via-black to-[#410000] rounded-xl shadow-inner">
    <h3 class="text-xl font-bold text-white mb-4">Clothing Items</h3>

    @if (count($clothingItems))
        <div class="flex overflow-x-auto space-x-4 pb-2">
            @foreach ($clothingItems as $clothing)
                @php
                    $boneName = app('App\Http\Controllers\MiscController')->getBoneName($clothing->boneid);
                @endphp
                <div class="flex-shrink-0 w-52 bg-neutral-900/60 rounded-lg border border-red-700 shadow-lg hover:shadow-xl transition duration-200 ease-in-out">
                    <!-- Image -->
                    <div class="h-40 rounded-t-lg overflow-hidden">
                        <img src="https://files.prineside.com/gtasa_samp_model_id/white/{{ $clothing->modelid }}_w.jpg"
                             alt="{{ $clothing['name'] }}" 
                             class="w-full h-full object-cover" />
                    </div>

                    <!-- Info -->
                    <div class="p-4">
                        <p class="text-base text-white font-semibold truncate mb-2">{{ $clothing->name }}</p>
                        <h4 class="text-sm text-gray-300">Status: <span class="text-gray-400">{{ $clothing->attached ? 'Attached' : 'Dettached' }}</h4>
                        <h4 class="text-sm text-gray-300">Bone: <span class="text-gray-400">{{ $boneName }}</h4>
                        <h4 class="text-sm text-gray-300">Object ID: <span class="text-gray-400">{{ $clothing->modelid }}</h4>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-400 italic">No clothing items equipped.</p>
    @endif
</div>
