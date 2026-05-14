import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('alpine:init', () => {
    Alpine.data('linkModal', () => ({
        open: false,
        linkUrl: '',
        linkText: '',
        linkTarget: '',
        activeTab: 'link',

        init() {
            console.log('Alpine компонент загружен');
        },

        insertLink() {
            console.log('insertLink вызван', this.linkUrl);
            this.open = false;
        },

        close() {
            this.open = false;
        }
    }));
});
