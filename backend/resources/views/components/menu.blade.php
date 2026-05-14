@if($items->isNotEmpty())
    <ul class="menu menu-level-0">
        @foreach($items as $item)
            <li class="menu-item menu-item-level-{{ $item->level }}">
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
                    <ul class="menu-submenu menu-level-{{ $item->level + 1 }}">
                        @foreach($item->children as $child)
                            <li class="menu-item menu-item-level-{{ $child->level }}">
                                <a href="{{ $child->url }}"
                                   target="{{ $child->target }}"
                                   class="menu-link">
                                    @if($child->icon)
                                        <i class="menu-icon">{{ $child->icon }}</i>
                                    @endif
                                    <span class="menu-title">{{ $child->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@endif
