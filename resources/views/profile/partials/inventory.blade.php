@php
    $itemsData = [
        ['item' => 'phone',          'object' => 18871],
        ['item' => 'walkietalkie',   'object' => 330],
        ['item' => 'gps',            'object' => 19164],
        ['item' => 'backpack',       'object' => 2919],
        ['item' => 'cash',           'object' => 1274],
        ['item' => 'dirtycash',      'object' => 1550],
        ['item' => 'grilledsalmon',  'object' => 1582], // Mapped as Pizza for now
        ['item' => 'grilledtuna',    'object' => 2880], // Mapped as Burger
        ['item' => 'grilledsardines','object' => 2769], // Mapped as Nachos
        ['item' => 'applejuice',     'object' => 19835], // Sprunk
        ['item' => 'pineapplejuice', 'object' => 19570], // Water
        ['item' => 'weaponclipp',    'object' => 19832],
        ['item' => 'weaponclips',    'object' => 19832],
        ['item' => 'weaponclip',     'object' => 19832],
        ['item' => 'weaponclipr',    'object' => 19832],
        ['item' => 'weaponclipsr',   'object' => 19832],
        ['item' => 'helmet1',        'object' => 19107],
        ['item' => 'vest',           'object' => 373],
        ['item' => 'medkit',         'object' => 11738],
        ['item' => 'bandage',        'object' => 11747],
        ['item' => 'pot',            'object' => 1578],
        ['item' => 'crack',          'object' => 1579],
        ['item' => 'meth',           'object' => 1580],
        ['item' => 'seeds',          'object' => 1279], // Kush Seeds
        ['item' => 'cocaseeds',      'object' => 1279],
        ['item' => 'ephedrineseeds', 'object' => 1279],
        ['item' => 'materials',      'object' => 1575],
        ['item' => 'hmaterials',     'object' => 1577],
        ['item' => 'repairkit',      'object' => 19627],
        ['item' => 'lockpick',       'object' => 19804],
        ['item' => 'cigars',         'object' => 19625], // Mapped from "Cigarette"
        ['item' => 'fishingbait',    'object' => 19063],
        ['item' => 'rope',           'object' => 19087],
        ['item' => 'blindfold',      'object' => 18912],
        ['item' => 'gascan',         'object' => 19998],
        ['item' => 'fishingrod',     'object' => 18632],
        ['item' => 'mp3player',      'object' => 19381],
        ['item' => 'fish1',          'object' => 19630], // Small Fish
        ['item' => 'fish2',          'object' => 19630], // Medium Fish
        ['item' => 'fish3',          'object' => 19630], // Large Fish
        ['item' => 'fish4',          'object' => 19630], // Exotic Fish
    ];
@endphp

@foreach ($itemsData as $data)
    @php
        $item = $data['item'];
        $object = $data['object'];
        $count = auth()->user()->$item;
    @endphp

    @if ($count > 0)
        <div class="flex rounded-xl border border-gray-100/30 shadow overflow-hidden min-h-[96px]">
            <!-- Left: Full Image -->
            <div class="w-1/3">
                <img src="https://files.prineside.com/gtasa_samp_model_id/white/{{ $object }}_w.jpg"
                     alt="{{ $item }}" 
                     class="w-full h-full object-cover" />
            </div>

            <!-- Right: Info -->
            <div class="w-2/3 p-4 flex flex-col justify-center">
                <h4 class="text-sm text-gray-400">{{ ucfirst(str_replace('_', ' ', $item)) }}</h4>
                <p class="text-lg text-white font-semibold">{{ $count }}</p>
            </div>
        </div>
    @endif
@endforeach

@if (collect($itemsData)->every(fn($i) => auth()->user()->{$i['item']} == 0))
    <li class="text-gray-500 italic">No items in inventory.</li>
@endif