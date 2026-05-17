@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-4xl font-bold mb-8 text-center">Последние материалы</h1>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($materials as $material)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="{{ $material->getUrl() }}" class="hover:text-blue-600">
                                {{ $material->title }}
                            </a>
                        </h2>
                        <p class="text-gray-600">{{ Str::limit($material->content, 100) }}</p>
                    </div>
                @empty
                    <p class="text-center col-span-3">Нет материалов для отображения.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
