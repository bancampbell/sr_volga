@push('styles')
    <link href="{{ Vite::asset('resources/css/file-manager.css') }}" rel="stylesheet">
@endpush

<div>
    <button type="button" wire:click="openModalWithSelectedText" class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
        🔗 Добавить ссылку
    </button>

    @if($open)
        <div class="link-modal-overlay">
            <div class="link-modal-container">
                <div class="link-modal-header">
                    <h3>Ссылка</h3>
                    <button type="button" wire:click="closeModal" class="link-modal-close">×</button>
                </div>

                <div class="link-modal-tabs">
                    <button type="button" wire:click="$set('activeTab', 'link')" class="link-modal-tab {{ $activeTab === 'link' ? 'active' : '' }}">Ссылка</button>
                    <button type="button" wire:click="$set('activeTab', 'advanced')" class="link-modal-tab {{ $activeTab === 'advanced' ? 'active' : '' }}">Расширенные</button>
                    <button type="button" wire:click="$set('activeTab', 'popup')" class="link-modal-tab {{ $activeTab === 'popup' ? 'active' : '' }}">Всплывающие окна</button>
                </div>

                <div class="link-modal-body">
                    @if($activeTab === 'link')
                        <div>
                            <div class="form-group">
                                <label class="form-label">Адрес</label>
                                <div class="input-group">
                                    <input type="text" wire:model="linkUrl" class="form-input">
                                    <button type="button" wire:click="openFileManager" class="btn-icon">📄</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Текст ссылки</label>
                                <input type="text" wire:model="linkText" class="form-input">
                            </div>

                            <div class="search-section">
                                <div class="search-box">
                                    <input type="text" wire:model.live="searchTerm" placeholder="Поиск..." class="search-input">
                                    <button type="button" wire:click="loadMaterials" class="search-button">🔍 Поиск</button>
                                </div>

                                <div class="category-tree-container">
                                    @if($searchTerm)
                                        @forelse($materials as $material)
                                            <div wire:click="selectMaterial('{{ $material->slug }}')"
                                                 class="material-item {{ ($selectedMaterialId ?? null) === $material->id ? 'selected' : '' }}">
                                                {{ $material->title }}
                                            </div>
                                        @empty
                                            <div class="empty-message">Материалы не найдены</div>
                                        @endforelse
                                    @else
                                        @foreach($categories as $category)
                                            @include('livewire.category-tree', ['category' => $category, 'selectedMaterialId' => $selectedMaterialId])
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Цель</label>
                                <select wire:model="linkTarget" class="form-select">
                                    <option value="">-- Не выбрано --</option>
                                    <option value="_blank">_blank</option>
                                    <option value="_self">_self</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Название</label>
                                <input type="text" wire:model="linkTitle" class="form-input">
                            </div>
                        </div>
                    @endif

                    @if($activeTab === 'advanced')
                        <div style="text-align: center; padding: 2rem; color: #6b7280;">Расширенные настройки (будет позже)</div>
                    @endif

                    @if($activeTab === 'popup')
                        <div style="text-align: center; padding: 2rem; color: #6b7280;">Настройка всплывающих окон (будет позже)</div>
                    @endif
                </div>

                <div class="link-modal-footer">
                    <button type="button" wire:click="closeModal" class="btn-cancel">Отмена</button>
                    <button type="button" wire:click="insertLink" class="btn-primary">Вставить ссылку</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Модальное окно файлового менеджера -->
    @if($showFileManagerModal)
        <div class="fm-modal-overlay">
            <div class="fm-modal-container">
                <div class="fm-modal-header">
                    <h3>Файловый менеджер</h3>
                    <button type="button" wire:click="closeFileManagerModal" class="fm-modal-close">×</button>
                </div>
                <div class="fm-modal-content">
                    @livewire('file-manager', ['key' => time()])
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('get-selected-text', () => {
            const editorElement = document.querySelector('.tiptap');
            if (editorElement && editorElement.editor) {
                const editor = editorElement.editor;
                const { state } = editor;
                const { from, to } = state.selection;

                let selectedText = state.doc.textBetween(from, to, ' ');

                if (from === to && selectedText === '') {
                    const marks = state.doc.resolve(from).marks();
                    const linkMark = marks.find(mark => mark.type.name === 'link');
                    if (linkMark) {
                        let start = from;
                        let end = from;
                        while (start > 0) {
                            const markAtPos = state.doc.resolve(start - 1).marks().find(m => m.type.name === 'link');
                            if (!markAtPos) break;
                            start--;
                        }
                        while (end < state.doc.content.size) {
                            const markAtPos = state.doc.resolve(end).marks().find(m => m.type.name === 'link');
                            if (!markAtPos) break;
                            end++;
                        }
                        selectedText = state.doc.textBetween(start, end, ' ');
                    @this.set('linkUrl', linkMark.attrs.href || '');
                    @this.set('linkText', selectedText);
                    }
                }

                if (selectedText) {
                @this.set('linkText', selectedText);
                }
            }
        @this.call('openModal');
        });

        Livewire.on('insert-link', (data) => {
            const editorElement = document.querySelector('.tiptap');
            if (editorElement && editorElement.editor) {
                const editor = editorElement.editor;
                const { state } = editor;
                const { from, to } = state.selection;

                const newLinkText = data.text || data.url;

                const marks = state.doc.resolve(from).marks();
                const linkMark = marks.find(mark => mark.type.name === 'link');

                let deleteFrom = from;
                let deleteTo = to;

                if (linkMark) {
                    let start = from;
                    let end = from;
                    while (start > 0) {
                        const markAtPos = state.doc.resolve(start - 1).marks().find(m => m.type.name === 'link');
                        if (!markAtPos) break;
                        start--;
                    }
                    while (end < state.doc.content.size) {
                        const markAtPos = state.doc.resolve(end).marks().find(m => m.type.name === 'link');
                        if (!markAtPos) break;
                        end++;
                    }
                    deleteFrom = start;
                    deleteTo = end;
                }

                if (deleteFrom !== deleteTo) {
                    editor.commands.deleteRange({ from: deleteFrom, to: deleteTo });
                }

                const linkHtml = `<a href="${data.url}" target="${data.target}" title="${data.title}">${newLinkText}</a>`;
                editor.commands.insertContent(linkHtml);
            } else {
                console.error('Editor not found');
            }
        });
    });
</script>
