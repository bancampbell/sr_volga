@extends('layouts.app')

@section('title', $material->title ?? $homeMaterial->title ?? '')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">

            @php
                $currentMaterial = $material ?? $homeMaterial ?? null;
                $breadcrumbs = $currentMaterial ? $currentMaterial->getBreadcrumbs() : [];
            @endphp

            @if(!request()->is('/'))
                @include('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
            @endif

            <article>
                <h1 class="text-4xl font-bold mb-4">{{ $currentMaterial->title ?? '' }}</h1>
                <div class="prose max-w-none">
                    {!! $currentMaterial->content ?? '' !!}
                </div>
            </article>
        </div>
    </div>
@endsection
