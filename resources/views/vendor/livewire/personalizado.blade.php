<div wire:key="pagination-{{ $paginator->currentPage() }}">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-end">
            <span class="relative z-0 inline-flex rounded-md shadow-sm">

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="Previous">
                        <span
                            class="relative inline-flex items-center justify-center px-1.5 py-2 text-xs font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-l-md leading-5 select-none"
                            style="min-height: 30px;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>
                @else
                    <button type="button" wire:click.prevent="setPage('{{ $paginator->previousPageUrl() }}')"
                        class="relative inline-flex items-center justify-center px-1.5 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none transition ease-in-out duration-150"
                        style="min-height: 30px;">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span
                            class="relative inline-flex items-center justify-center px-3 py-1.5 -ml-px text-xs font-medium text-gray-500 bg-white border border-gray-300 leading-5 select-none"
                            style="min-height: 30px;">
                            {{ $element }}
                        </span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span
                                        class="relative inline-flex items-center justify-center px-3 py-1.5 -ml-px text-xs font-medium text-white bg-blue-600 border border-blue-600 leading-5 select-none"
                                        style="min-height: 30px;">
                                        {{ $page }}
                                    </span>
                                </span>
                            @else
                                <button type="button" wire:click.prevent="setPage('{{ $url }}')"
                                    class="relative inline-flex items-center justify-center px-3 py-1.5 -ml-px text-xs font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-blue-600 hover:border-blue-400 hover:bg-blue-50 focus:z-10 focus:outline-none transition ease-in-out duration-150"
                                    style="min-height: 30px;">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button type="button" wire:click.prevent="setPage('{{ $paginator->nextPageUrl() }}')"
                        class="relative inline-flex items-center justify-center px-1.5 py-2 -ml-px text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none transition ease-in-out duration-150"
                        style="min-height: 30px;">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                @else
                    <span aria-disabled="true" aria-label="Next">
                        <span
                            class="relative inline-flex items-center justify-center px-1.5 py-2 -ml-px text-xs font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-r-md leading-5 select-none"
                            style="min-height: 30px;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>
                @endif

            </span>
        </nav>
    @endif
</div>
