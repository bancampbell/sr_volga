<div x-data="{}" class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
    <!-- Верхняя панель -->
    <div class="bg-white px-4 py-2">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ filament()->getUrl() }}"
                   class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 transition-colors"
                   title="На главную админки">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
                <span class="text-sm text-gray-600">Путь:</span>
                <div class="flex items-center gap-1">
                    <button wire:click="goBack" class="text-sm text-blue-600 hover:underline">Назад</button>
                    <span class="text-gray-400">/</span>
                    <span class="text-sm font-mono text-gray-700">{{ $currentPath ?: '/' }}</span>
                </div>
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-1 text-sm bg-gray-100 border border-gray-300 rounded hover:bg-gray-200">Показать Все</button>
                <button class="px-3 py-1 text-sm bg-gray-100 border border-gray-300 rounded hover:bg-gray-200">Загрузить</button>
                <button class="px-3 py-1 text-sm bg-gray-100 border border-gray-300 rounded hover:bg-gray-200">Справка</button>
            </div>
        </div>
    </div>

    <div class="flex">
        <!-- Левая панель -->
        <!-- Левая панель -->
        <div class="w-64 border-r border-gray-200">
            <div class="bg-gray-100 px-3 py-2">
                <h4 class="text-xs font-semibold text-gray-600 uppercase">Папки</h4>
            </div>

            <!-- Поле для новой папки -->
            <div class="p-2 border-b border-gray-200">
                <div class="flex gap-1">
                    <input type="text"
                           wire:model="newFolderName"
                           wire:keydown.enter="createFolderInline"
                           placeholder="Новая папка..."
                           class="flex-1 text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <button wire:click="createFolderInline"
                            class="px-2 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                        +
                    </button>
                </div>
                @error('newFolderName')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="p-2 bg-white">
                @include('livewire.folder-tree', ['folders' => $foldersTree])
            </div>
        </div>

        <!-- Центральная область -->
        <div class="flex-1 border-r border-gray-200">
            <div class="bg-gray-100 px-3 py-2">
                <h4 class="text-xs font-semibold text-gray-600 uppercase">Имя</h4>
            </div>
            <div class="p-2 bg-white">
                <div class="space-y-1">
                    @foreach($files as $item)
                        @if($item['type'] === 'file')
                            @php
                                $isSelected = $selectedFile && $selectedFile['path'] === $item['path'];
                            @endphp
                            <div wire:click="showFileDetails('{{ $item['path'] }}')"
                                 wire:dblclick="insertFileUrl('{{ $item['url'] }}')"
                                 class="text-sm px-2 py-1 rounded cursor-pointer transition-all {{ !$isSelected ? 'text-gray-700 hover:bg-gray-100' : '' }}"
                                 @if($isSelected) style="font-weight: 900 !important; color: #111827; background-color: #e0f2fe;" @endif>
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
        <div class="w-64">
            <div class="bg-gray-100 px-3 py-2">
                <h4 class="text-xs font-semibold text-gray-600 uppercase">Подробная информация</h4>
            </div>
            <div class="p-3 bg-white">
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
