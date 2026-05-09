/** @type {import("tailwindcss").Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./app/Livewire/**/*.php",
        "./app/Filament/**/*.php",
        "./app/Http/**/*.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
