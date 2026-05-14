<div>
    <div wire:click.stop="toggleCategory({{ $category->id }})"
         class="category-item {{ in_array($category->id, $expandedCategories) ? 'font-medium' : '' }}">
        <span class="category-expand">{{ in_array($category->id, $expandedCategories) ? '−' : '+' }}</span>
        <span>{{ $category->name }} ({{ $category->materials_count ?? $category->materials->count() }})</span>
    </div>

    @if(in_array($category->id, $expandedCategories))
        <div class="category-children">
            @foreach($category->materials as $material)
                <div wire:click="selectMaterial('{{ $material->slug }}')"
                     class="material-item {{ ($selectedMaterialId ?? null) === $material->id ? 'selected' : '' }}">
                    {{ $material->title }}
                </div>
            @endforeach
        </div>
    @endif
</div>
