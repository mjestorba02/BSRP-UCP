@php
    $items = [
        'phone',
        'walkietalkie',
        'gps',
        'backpack',
        'cash',
        'dirtycash',
        'grilledsalmon',
        'grilledtuna',
        'grilledsardines',
        'applejuice',
        'pineapplejuice',
        'weaponclipp',
        'weaponclips',
        'weaponclip',
        'weaponclipr',
        'weaponclipsr',
        'helmet1',
        'vest',
        'medkit',
        'bandage',
        'pot',
        'crack',
        'meth',
        'seeds',
        'cocaseeds',
        'ephedrineseeds',
        'materials',
        'hmaterials',
        'repairkit',
        'lockpick',
        'cigars',
        'fishingbait',
        'rope',
        'blindfold',
        'gascan',
        'fishingrod',
        'mp3player',
        'fish1',
        'fish2',
        'fish3',
        'fish4',
    ];
@endphp

@foreach ($items as $item)
    @if (auth()->user()->$item > 0)
        <div class="rounded-xl border border-gray-100/30 p-4 flex items-center space-x-4 shadow">
            <div>
                <h4 class="text-sm text-gray-400">{{ ucfirst(str_replace('_', ' ', $item)) }}</h4>
                <p class="text-lg text-white font-semibold">{{ auth()->user()->$item }}</p>
            </div>
        </div>
    @endif
@endforeach

@if (collect($items)->every(fn($i) => auth()->user()->$i == 0))
    <li class="text-gray-500 italic">No items in inventory.</li>
@endif