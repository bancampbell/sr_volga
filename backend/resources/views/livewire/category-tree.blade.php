<div>
    <div wire:click.stop="toggleCategory({{ $category->id }})"
         style="padding: 0.5rem 0.75rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; {{ in_array($category->id, $expandedCategories) ? 'font-weight: 500;' : '' }}"
         onmouseover="this.style.backgroundColor='#f3f4f6'"
         onmouseout="this.style.backgroundColor='transparent'">
        <span style="font-size: 1rem; font-weight: bold; color: #374151;">{{ in_array($category->id, $expandedCategories) ? '−' : '+' }}</span>
        <span>{{ $category->name }} ({{ $category->materials_count ?? $category->materials->count() }})</span>
    </div>

    @if(in_array($category->id, $expandedCategories))
        <div style="margin-left: 1.5rem;">
            @foreach($category->children as $child)
                @include('livewire.category-tree', ['category' => $child, 'selectedMaterialId' => $selectedMaterialId])
            @endforeach

            @foreach($category->materials as $material)
                <div wire:click="selectMaterial('{{ $material->slug }}')"
                     style="padding: 0.5rem 0.75rem 0.5rem 1rem; cursor: pointer; {{ ($selectedMaterialId ?? null) === $material->id ? 'font-weight: 900; background-color: #e0f2fe;' : '' }}"
                     onmouseover="this.style.backgroundColor='#f3f4f6'"
                     onmouseout="this.style.backgroundColor='{{ ($selectedMaterialId ?? null) === $material->id ? '#e0f2fe' : 'transparent' }}'">
                    {{ $material->title }}
                </div>
            @endforeach
        </div>
    @endif
</div>
