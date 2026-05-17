@if($breadcrumbs)
    <nav class="text-sm mb-6">
        <ol class="flex flex-wrap items-center gap-1 text-gray-600">
            <li>
                <a href="{{ url('/') }}" class="hover:text-blue-600">Главная</a>
            </li>
            @foreach($breadcrumbs as $crumb)
                <li class="flex items-center gap-1">
                    <span class="text-gray-400">/</span>
                    @if(!$loop->last && isset($crumb['url']))
                        <a href="{{ $crumb['url'] }}" class="hover:text-blue-600">{{ $crumb['name'] }}</a>
                    @else
                        <span class="text-gray-800">{{ $crumb['name'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
