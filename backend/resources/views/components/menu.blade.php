@if($items->isNotEmpty())
    <ul class="menu menu-level-{{ $level ?? 0 }}">
        @foreach($items as $item)
            <li class="menu-item menu-item-level-{{ $level ?? 0 }}">
                <a href="{{ $item->url }}"
                   target="{{ $item->target }}"
                   class="menu-link"
                   @if($item->is_new_tab) rel="noopener noreferrer" @endif>
                    @if($item->icon)
                        <i class="menu-icon">{{ $item->icon }}</i>
                    @endif
                    <span class="menu-title">{{ $item->name }}</span>
                </a>

                @if($item->children->isNotEmpty())
                    <ul class="menu-submenu">
                        @foreach($item->children as $child)
                            <li class="menu-submenu-item">
                                <a href="{{ $child->url }}" target="{{ $child->target }}">{{ $child->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@endif
