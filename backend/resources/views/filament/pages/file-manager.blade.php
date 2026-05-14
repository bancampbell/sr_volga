<link href="{{ Vite::asset('resources/css/app.css') }}" rel="stylesheet">
<link href="{{ Vite::asset('resources/css/file-manager.css') }}" rel="stylesheet">
<script src="{{ Vite::asset('resources/js/app.js') }}"></script>

@filamentStyles
@filamentScripts

<div>
    @livewire('file-manager')
</div>

@livewireScripts
