<div>
    <button type="button" wire:click="openModalWithSelectedText" class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
        🔗 Добавить ссылку
    </button>

    @if($open)
        <div style="position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 0.5rem; width: 700px; max-width: 90%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">Ссылка</h3>
                    <button type="button" wire:click="closeModal" style="color: #9ca3af; font-size: 1.5rem; background: none; border: none; cursor: pointer;">&times;</button>
                </div>

                <div style="padding: 0 1rem; border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
                    <div style="display: flex; gap: 1rem;">
                        <button type="button" wire:click="$set('activeTab', 'link')" style="padding: 0.75rem 0; border-bottom: 2px solid {{ $activeTab === 'link' ? '#4f46e5' : 'transparent' }}; color: {{ $activeTab === 'link' ? '#4f46e5' : '#4b5563' }}; font-weight: 500; background: none; cursor: pointer;">Ссылка</button>
                        <button type="button" wire:click="$set('activeTab', 'advanced')" style="padding: 0.75rem 0; border-bottom: 2px solid {{ $activeTab === 'advanced' ? '#4f46e5' : 'transparent' }}; color: {{ $activeTab === 'advanced' ? '#4f46e5' : '#4b5563' }}; font-weight: 500; background: none; cursor: pointer;">Расширенные</button>
                        <button type="button" wire:click="$set('activeTab', 'popup')" style="padding: 0.75rem 0; border-bottom: 2px solid {{ $activeTab === 'popup' ? '#4f46e5' : 'transparent' }}; color: {{ $activeTab === 'popup' ? '#4f46e5' : '#4b5563' }}; font-weight: 500; background: none; cursor: pointer;">Всплывающие окна</button>
                    </div>
                </div>

                <div style="padding: 1.5rem;">
                    @if($activeTab === 'link')
                        <div>
                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Адрес</label>
                                <div style="display: flex; gap: 0.5rem;">
                                    <input type="text" wire:model="linkUrl" style="flex: 1; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;">
                                    <button type="button" wire:click="openFileManager" style="padding: 0.5rem 0.75rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: pointer; display: inline-flex; align-items: center;">
                                        📄
                                    </button>
                                </div>
                            </div>
                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Текст ссылки</label>
                                <input type="text" wire:model="linkText" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;">
                            </div>
                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Поиск</label>
                                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <input type="text" placeholder="Поиск..." style="flex: 1; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;">
                                    <button type="button" style="padding: 0.5rem 1rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer;">Поиск</button>
                                </div>
                                <div style="border: 1px solid #e5e7eb; border-radius: 0.375rem; overflow: hidden;">
                                    <div style="padding: 0.5rem; cursor: pointer; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">Контакты</div>
                                    <div style="padding: 0.5rem; cursor: pointer; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">Контент</div>
                                    <div style="padding: 0.5rem; cursor: pointer; background: #f9fafb;">Меню</div>
                                </div>
                            </div>
                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Якоря статьи</label>
                                <select style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;">
                                    <option>-- Выбрать --</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Цель</label>
                                <select wire:model="linkTarget" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;">
                                    <option value="">-- Не выбрано --</option>
                                    <option value="_blank">_blank</option>
                                    <option value="_self">_self</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Название</label>
                                <input type="text" wire:model="linkTitle" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem;">
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

                <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding: 1rem; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                    <button type="button" wire:click="closeModal" style="padding: 0.5rem 1rem; background: #e5e7eb; border-radius: 0.375rem; border: none; cursor: pointer;">Отмена</button>
                    <button type="button" wire:click="insertLink" style="padding: 0.5rem 1rem; background: #4f46e5; color: white; border-radius: 0.375rem; border: none; cursor: pointer;">Вставить ссылку</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Модальное окно файлового менеджера -->
    @if($showFileManagerModal)
        <link rel="stylesheet" href="{{ asset('build/app.css') }}">
        <div style="position: fixed; inset: 0; z-index: 10000; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 0.5rem; width: 1000px; max-width: 90%; height: 80vh; display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600;">Файловый менеджер</h3>
                    <button type="button" wire:click="closeFileManagerModal" style="color: #9ca3af; font-size: 1.5rem; background: none; border: none; cursor: pointer;">&times;</button>
                </div>
                <div style="flex: 1; overflow: auto; padding: 1rem;">
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
