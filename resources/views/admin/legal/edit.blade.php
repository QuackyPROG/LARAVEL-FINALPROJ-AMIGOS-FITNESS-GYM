@extends('layouts.admin')

@section('title', $title)

@section('content')

<div>
    <a href="{{ route('admin.legal.index') }}">
        Legal Documents
    </a>
    <span>/</span>
    <span>{{ $title }}</span>
</div>

<livewire:admin.legal-document-editor :slug="$slug" />

@endsection
