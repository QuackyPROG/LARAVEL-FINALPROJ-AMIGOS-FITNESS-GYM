@extends('layouts.admin')

@section('title', $title)

@section('content')

<div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="{{ route('admin.legal.index') }}" class="underline">
        Legal Documents
    </a>
    <span>/</span>
    <span class="text-gray-900 font-medium">{{ $title }}</span>
</div>

<livewire:admin.legal-document-editor :slug="$slug" />

@endsection
