@extends('layouts.admin')
@section('title', $coach->name . ' — Coach Dashboard')
@section('content')
    <livewire:admin.coach-dashboard :coach="$coach" />
@endsection
