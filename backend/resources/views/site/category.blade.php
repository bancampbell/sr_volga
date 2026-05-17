@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">

            @include('components.breadcrumbs', ['breadcrumbs' => $category->getBreadcrumbs()])

            <h1 class="text-3xl font-bold mb-8">{{ $category->name }}</h1>

            @forelse($materials as $material)
                <div class="mb-6 pb-6 border-b">
                    <h2 class="text-2xl font-semibold mb-2">
                        <a href="{{ $material->getUrl() }}" class="hover:text-blue-600">
                            {{ $material->title }}
                        </a>
                    </h2>
                    <p class="text-gray-600">{{ Str::limit($material->content, 200) }}</p>
                </div>
            @empty
                <p>Нет материалов в этой категории.</p>
            @endforelse

            {{ $materials->links() }}
        </div>
    </div>
@endsection
