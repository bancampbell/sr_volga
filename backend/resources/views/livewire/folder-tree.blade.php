@foreach($folders as $folder)
    <div x-data="{ open: false }" class="ml-0">
        <div @click="open = !open; $wire.selectFolder('{{ $folder['path'] }}')" class="text-sm text-gray-700 hover:bg-gray-100 px-2 py-1 rounded cursor-pointer flex items-center">
            <span class="mr-1">📁</span> {{ $folder['name'] }}
        </div>
        <div x-show="open" x-cloak class="ml-4">
            @if(count($folder['children']) > 0)
                @include('livewire.folder-tree', ['folders' => $folder['children']])
            @endif
        </div>
    </div>
@endforeach
