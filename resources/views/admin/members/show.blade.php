@extends('layouts.admin')

@section('title', 'Member Detail')

@section('content')
<livewire:admin.member-detail :member="$member" />
@endsection
