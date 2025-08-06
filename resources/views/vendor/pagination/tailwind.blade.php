@if ($paginator->hasPages())
    <nav class="flex justify-center mt-6">
        <ul class="inline-flex items-center -space-x-px text-sm">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-4 py-2 rounded-l-lg bg-neutral-800 text-gray-400 cursor-not-allowed">‹</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 rounded-l-lg bg-red-700 text-white hover:bg-red-800">‹</a>
                </li>
            @endif

            {{-- Page Number Links --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span class="px-4 py-2 bg-neutral-700 text-gray-300">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-4 py-2 bg-red-800 text-white font-bold">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-4 py-2 bg-neutral-800 text-white hover:bg-red-700">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 rounded-r-lg bg-red-700 text-white hover:bg-red-800">›</a>
                </li>
            @else
                <li>
                    <span class="px-4 py-2 rounded-r-lg bg-neutral-800 text-gray-400 cursor-not-allowed">›</span>
                </li>
            @endif
        </ul>
    </nav>
@endif