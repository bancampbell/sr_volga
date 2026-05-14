


<div x-data="{}" class="file-manager-container">
    <!-- Верхняя панель -->
    <div class="fm-header">
        <div class="fm-header-content">
            <div class="fm-breadcrumb">
                <a href="{{ filament()->getUrl() }}" class="fm-home-icon" title="На главную админки">
                    <svg class="fm-home-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
                <span class="fm-breadcrumb-text">Путь:</span>
                <div class="fm-breadcrumb-nav">
                    <button wire:click="goBack" class="fm-breadcrumb-back">Назад</button>
                    <span class="fm-breadcrumb-sep">/</span>
                    <span class="fm-breadcrumb-path">{{ $currentPath ?: '/' }}</span>
                </div>
            </div>
            <div class="fm-actions">
                <button wire:click="openUploadModal" class="fm-btn-upload">Загрузить</button>
                <button class="fm-btn-help">Справка</button>
            </div>
        </div>
    </div>

    <div class="fm-main-layout">
        <!-- Левая панель -->
        <div class="fm-sidebar">
            <div class="fm-sidebar-header">
                <h4 class="fm-sidebar-title">Папки</h4>
            </div>

            <div class="fm-new-folder">
                <div class="fm-new-folder-form">
                    <input type="text"
                           wire:model="newFolderName"
                           wire:keydown.enter="createFolderInline"
                           placeholder="Новая папка..."
                           class="fm-new-folder-input">
                    <button wire:click="createFolderInline" class="fm-new-folder-btn">+</button>
                </div>
                @error('newFolderName')
                <div class="fm-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="fm-folder-tree">
                @include('livewire.folder-tree', ['folders' => $foldersTree])
            </div>
        </div>

        <!-- Центральная область -->
        <div class="fm-content">
            <div class="fm-content-header">
                <h4 class="fm-content-title">Имя</h4>
            </div>
            <div class="fm-file-list">
                <div class="fm-file-list-inner">
                    @foreach($files as $item)
                        @if($item['type'] === 'file')
                            @php
                                $isSelected = $selectedFile && $selectedFile['path'] === $item['path'];
                            @endphp
                            <div class="fm-file-item {{ !$isSelected ? 'fm-file-item-unselected' : 'fm-file-item-selected' }}">
                                <div class="fm-checkbox">
                                    @if($isSelected)
                                        <span class="fm-checkbox-mark">✓</span>
                                    @endif
                                </div>
                                <span wire:click="toggleFileSelection('{{ $item['path'] }}')"
                                      wire:dblclick="insertFileUrl('{{ $item['url'] }}')"
                                      class="fm-file-name">
                                    {{ $item['name'] }}
                                </span>
                            </div>
                        @endif
                    @endforeach
                    @if(count($files) === 0)
                        <div class="fm-empty">(нет файлов)</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Правая панель -->
        <div class="fm-info">
            <div class="fm-info-header">
                <h4 class="fm-info-title">Подробная информация</h4>
            </div>
            <div class="fm-info-content">
                @if($selectedFile)
                    <div class="fm-info-preview">
                        <div class="fm-info-name">{{ $selectedFile['name'] }}</div>
                        <div class="fm-info-type">{{ strtoupper(pathinfo($selectedFile['name'], PATHINFO_EXTENSION)) }} Файл</div>
                    </div>
                    <div class="fm-info-details">
                        <div class="fm-info-row">
                            <span class="fm-info-label">Размер:</span>
                            <span>{{ $selectedFile['size'] }}</span>
                        </div>
                        <div class="fm-info-row">
                            <span class="fm-info-label">Изменено:</span>
                            <span>{{ $selectedFile['lastModified'] }}</span>
                        </div>
                    </div>
                @else
                    <div class="fm-info-empty">Выберите файл</div>
                @endif
            </div>
            @if($selectedFile)
                <div class="fm-info-actions">
                    <button wire:click="insertFileUrl('{{ $selectedFile['url'] }}')" class="fm-btn-insert">Вставить</button>
                    <button wire:click="deleteSelectedFile" class="fm-btn-delete">Удалить</button>
                    <button wire:click="closeFileInfo" class="fm-btn-cancel">Отменить</button>
                </div>
            @endif
        </div>
    </div>

    <!-- Модальное окно загрузки файлов -->
    @if($showUploadModal)
        <div class="upload-modal-overlay" wire:click.self="closeUploadModal">
            <div class="upload-modal-container">
                <div class="upload-modal-header">
                    <span class="upload-modal-title">Загрузить</span>
                </div>

                <div class="upload-dropzone"
                     x-data
                     x-on:dragover.prevent="$el.classList.add('dragover')"
                     x-on:dragleave.prevent="$el.classList.remove('dragover')"
                     x-on:drop.prevent="
                     $el.classList.remove('dragover');
                     let files = Array.from($event.dataTransfer.files);
                     $wire.uploadMultiple('uploadedFiles', files);
                 ">
                    <span class="upload-dropzone-text">Перетащите файлы сюда</span>
                </div>

                @if($uploadedFiles && count($uploadedFiles) > 0)
                    <div class="upload-file-list">
                        <div class="upload-file-list-header">Выбрано файлов: {{ count($uploadedFiles) }}</div>
                        @foreach($uploadedFiles as $file)
                            <div class="upload-file-item">
                                📄 {{ $file->getClientOriginalName() }}
                                ({{ $this->formatSize($file->getSize()) }})
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="upload-modal-footer">
                    <button type="button"
                            x-on:click="$refs.fileInput.click()"
                            class="upload-btn-browse">
                        Обзор
                    </button>
                    <input type="file"
                           x-ref="fileInput"
                           multiple
                           wire:model="uploadedFiles"
                           class="hidden">

                    <button wire:click="uploadFiles"
                            wire:loading.attr="disabled"
                            class="upload-btn-submit">
                        Загрузить
                    </button>

                    <button wire:click="closeUploadModal" class="upload-btn-close">Закрыть</button>
                </div>
            </div>
        </div>
    @endif
</div>
