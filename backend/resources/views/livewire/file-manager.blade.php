<div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
    <!-- Верхняя панель -->
    <div class="bg-white border-b border-gray-200 px-4 py-2">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Путь:</span>
                <div class="flex items-center gap-1">
                    <button wire:click="goBack" class="text-sm text-blue-600 hover:underline">Назад</button>
                    <span class="text-gray-400">/</span>
                    <span class="text-sm font-mono text-gray-700">{{ $currentPath ?: '/' }}</span>
                </div>
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-1 text-sm bg-gray-100 border border-gray-300 rounded hover:bg-gray-200">Показать Все</button>
                <button class="px-3 py-1 text-sm bg-gray-100 border border-gray-300 rounded hover:bg-gray-200">Новая папка</button>
                <button class="px-3 py-1 text-sm bg-gray-100 border border-gray-300 rounded hover:bg-gray-200">Загрузить</button>
                <button class="px-3 py-1 text-sm bg-gray-100 border border-gray-300 rounded hover:bg-gray-200">Справка</button>
            </div>
        </div>
    </div>

    <div class="flex">
        <!-- Левая панель (дерево папок) -->
        <div class="w-64 border-r border-gray-200">
            <div class="bg-gray-100 px-3 py-2 border-b border-gray-200">
                <h4 class="text-xs font-semibold text-gray-600 uppercase">Папки</h4>
            </div>
            <div class="p-2 bg-white h-96 overflow-y-auto">
                @include('livewire.folder-tree', ['folders' => $foldersTree])
            </div>
        </div>

        <!-- Центральная область (только файлы) -->
        <div class="flex-1 border-r border-gray-200">
            <div class="bg-gray-100 px-3 py-2 border-b border-gray-200">
                <h4 class="text-xs font-semibold text-gray-600 uppercase">Имя</h4>
            </div>
            <div class="p-2 bg-white h-96 overflow-y-auto">
                <div class="space-y-1">
                    @foreach($files as $item)
                        @if($item['type'] === 'file')
                            <div wire:click="showFileDetails('{{ $item['path'] }}')" class="text-sm text-gray-700 hover:bg-gray-100 px-2 py-1 rounded cursor-pointer">
                                {{ $item['name'] }}
                            </div>
                        @endif
                    @endforeach
                    @if(count($files) === 0)
                        <div class="text-sm text-gray-400 px-2 py-1">(нет файлов)</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Правая панель -->
        <div class="w-64 flex flex-col">
            <div class="bg-gray-100 px-3 py-2 border-b border-gray-200">
                <h4 class="text-xs font-semibold text-gray-600 uppercase">Подробная информация</h4>
            </div>
            <div class="p-3 bg-white flex-1">
                @if($selectedFile)
                    <div class="text-center">
                        <div class="font-medium text-sm break-all">{{ $selectedFile['name'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ strtoupper(pathinfo($selectedFile['name'], PATHINFO_EXTENSION)) }} Файл</div>
                    </div>
                    <div class="text-sm mt-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Размер:</span>
                            <span>{{ $selectedFile['size'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Изменено:</span>
                            <span>{{ $selectedFile['lastModified'] }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-gray-400 text-center py-8">
                        Выберите файл
                    </div>
                @endif
            </div>
            @if($selectedFile)
                <div class="border-t border-gray-200 p-3 flex gap-2">
                    <button wire:click="insertFileUrl('{{ $selectedFile['url'] }}')" class="flex-1 py-2 text-center text-white bg-blue-600 rounded hover:bg-blue-700">
                        Вставить
                    </button>
                    <button wire:click="closeFileInfo" class="flex-1 py-2 text-center text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                        Отменить
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
