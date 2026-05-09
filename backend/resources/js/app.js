import Alpine from 'alpinejs';
import 'tailwindcss';

document.addEventListener('alpine:init', () => {
    Alpine.data('linkModalComponent', () => ({
        open: false,
        linkUrl: '',
        linkText: '',
        linkTarget: '',
        activeTab: 'link',

        insertLink() {
            let editor = tinymce.activeEditor;
            if (editor) {
                let selectedText = editor.selection.getContent({format: 'text'});
                let linkTextValue = this.linkText || selectedText || this.linkUrl;
                let linkHtml = `<a href="${this.linkUrl}" target="${this.linkTarget}">${linkTextValue}</a>`;
                editor.insertContent(linkHtml);
            }
            this.open = false;
        }
    }));
});

window.Alpine = Alpine;
Alpine.start();

// Принудительная инициализация после Livewire
document.addEventListener('livewire:navigated', () => {
    if (typeof Alpine !== 'undefined') {
        setTimeout(() => {
            const el = document.querySelector('[x-data="linkModalComponent()"]');
            if (el && !el._x_dataStack) {
                Alpine.initTree(el);
            }
        }, 100);
    }
});
