@foreach($folders as $index => $folder)
    <div x-data="{ open{{ $index }}: false }" class="folder-tree-item">
        <div class="folder-tree-folder-wrapper">
            @if(count($folder['children']) > 0)
                <span @click="open{{ $index }} = !open{{ $index }}" class="folder-toggle">
                    <span x-show="!open{{ $index }}">➕</span>
                    <span x-show="open{{ $index }}" x-cloak>➖</span>
                </span>
            @else
                <span class="folder-toggle-empty">➕</span>
            @endif
            <div @click="if ({{ count($folder['children']) > 0 ? 'true' : 'false' }}) open{{ $index }} = !open{{ $index }}; $wire.selectFolder('{{ $folder['path'] }}')"
                 class="folder-tree-folder">
                <span class="folder-tree-icon">
                    <svg class="folder-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M2 6C2 4.89543 2.89543 4 4 4H9L12 7H20C21.1046 7 22 7.89543 22 9V18C22 19.1046 21.1046 20 20 20H4C2.89543 20 2 19.1046 2 18V6Z"
                              fill="#fbbf24" stroke="#d97706"/>
                    </svg>
                </span>
                <span>{{ $folder['name'] }}</span>
            </div>
        </div>
        @if(count($folder['children']) > 0)
            <div x-show="open{{ $index }}" x-cloak class="folder-tree-children">
                @include('livewire.folder-tree', ['folders' => $folder['children']])
            </div>
        @endif
    </div>
@endforeach
