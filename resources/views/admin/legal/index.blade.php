@extends('layouts.admin')

@section('title', 'Legal Documents')

@section('content')

<livewire:admin.legal-document-editor :documents="$documents" />

@endsection
