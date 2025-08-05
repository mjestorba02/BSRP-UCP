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

        <!-- Turfs -->
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Turfs</span>
            </div>
        </li>
    </ol>
</div>

<div class="p-6">
    <h1 class="text-xl text-white font-bold mb-4">Turf Info</h1>

    @if($turfs->isEmpty())
        <p class="text-white">The turf directory is currently empty.</p>
    @else
        <div class="overflow-x-auto rounded-lg border border-[#410000]">
            <table class="min-w-full text-sm text-white bg-black border-collapse">
                <thead>
                    <tr class="bg-[#410000] text-left">
                        <th class="p-3 border border-red-500">#</th>
                        <th class="p-3 border border-red-500">Turf Name</th>
                        <th class="p-3 border border-red-500">Location</th>
                        <th class="p-3 border border-red-500">Owner</th>
                        <th class="p-3 border border-red-500">Captured By</th>
                        <th class="p-3 border border-red-500">Type</th>
                        <th class="p-3 border border-red-500">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($turfs as $index => $turf)
                        @php
                            $zoneName = app('App\Http\Controllers\ProfileController')->getZoneName($turf->pos_x, $turf->pos_y, $turf->pos_z);
                        @endphp
                        <tr class="hover:bg-red-900">
                            <td class="p-3 border border-red-500">
                                {{ ($currentPage - 1) * $perPage + $index + 1 }}
                            </td>
                            <td class="p-3 border border-red-500">{{ $turf->name }}</td>
                            <td class="p-3 border border-red-500">{{ $zoneName }}</td>
                            <td class="p-3 border border-red-500">
                                @php
                                    $color = $turf->color & 0xFFFFFFFF;
                                    $rgb = ($color >> 8) & 0xFFFFFF;
                                    $r = ($rgb >> 16) & 0xFF;
                                    $g = ($rgb >> 8) & 0xFF;
                                    $b = $rgb & 0xFF;

                                    $hexColor = sprintf("#%06X", $rgb);
                                    $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
                                    $textColorClass = $brightness > 150 ? 'text-black' : 'text-white';
                                @endphp

                                <span class="{{ $textColorClass }} text-xs px-2 py-0.5 rounded" style="background-color: {{ $hexColor }}">
                                    {{ $turf->gang_name ?? 'Civilian' }}
                                </span>
                            </td>
                            <td class="p-3 border border-red-500">{{ $turf->capturedby }}</td>
                            <td class="p-3 border border-red-500">{{ $turf->type_label }}</td>
                            <td class="p-3 border border-red-500">{{ $turf->time }} hour/s</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between">
            @if($currentPage > 1)
                <a href="{{ route('misc.turfs', ['page' => $currentPage - 1]) }}"
                   class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-red-500">« Go Back</a>
            @endif

            @if($total > $currentPage * $perPage)
                <a href="{{ route('misc.turfs', ['page' => $currentPage + 1]) }}"
                   class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-red-500 ml-auto">Next »</a>
            @endif
        </div>
    @endif
</div>

<div class="p-6">
    <h1 class="text-xl text-white font-bold mb-4">Traphouse Info</h1>

    @if($thouse->isEmpty())
        <p class="text-white">The turf directory is currently empty.</p>
    @else
        <div class="overflow-x-auto rounded-lg border border-[#410000]">
            <table class="min-w-full text-sm text-white bg-black border-collapse">
                <thead>
                    <tr class="bg-[#410000] text-left">
                        <th class="p-3 border border-red-500">#</th>
                        <th class="p-3 border border-red-500">Traphouse Name</th>
                        <th class="p-3 border border-red-500">Location</th>
                        <th class="p-3 border border-red-500">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($thouse as $thindex => $th)
                        @php
                            $zoneName = app('App\Http\Controllers\ProfileController')->getZoneName($turf->pos_x, $turf->pos_y, $turf->pos_z);
                        @endphp
                        <tr class="hover:bg-red-900">
                            <td class="p-3 border border-[#410000]">
                                {{ ($thcurrentPage - 1) * $thperPage + $thindex + 1 }}
                            </td>
                            <td class="p-3 border border-red-500">{{ $th->name }}</td>
                            <td class="p-3 border border-red-500">{{ $zoneName }}</td>
                            <td class="p-3 border border-red-500">{{ $th->time }} hour/s</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between">
            @if($thcurrentPage > 1)
                <a href="{{ route('misc.turfs', ['thpage' => $thcurrentPage - 1]) }}"
                   class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-red-500">« Go Back</a>
            @endif

            @if($thtotal > $thcurrentPage * $thperPage)
                <a href="{{ route('misc.turfs', ['thpage' => $thcurrentPage + 1]) }}"
                   class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-red-500 ml-auto">Next »</a>
            @endif
        </div>
    @endif
</div>