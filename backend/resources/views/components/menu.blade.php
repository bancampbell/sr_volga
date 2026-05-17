@if($items->isNotEmpty())
    <ul class="menu">
        @foreach($items as $item)
            <li>
                <a href="{{ $item->url }}">{{ $item->name }}</a>
                @if($item->children->isNotEmpty())
                    <ul class="submenu">
                        @foreach($item->children as $child)
                            <li>
                                <a href="{{ $child->url }}">{{ $child->name }}</a>
                                @if($child->children->isNotEmpty())
                                    <ul class="submenu">
                                        @foreach($child->children as $grandChild)
                                            <li>
                                                <a href="{{ $grandChild->url }}">{{ $grandChild->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@endif
