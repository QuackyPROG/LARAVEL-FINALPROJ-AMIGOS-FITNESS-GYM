@extends('layouts.public')

@section('title', 'Become a Member')

@section('content')

<div class="max-w-2xl mx-auto px-6 py-12">
    <div class="mb-8">
        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-1">Registration</p>
        <h1 class="text-2xl font-semibold text-gray-900">Become a Member</h1>
        <p class="text-sm text-gray-500 mt-1">Fill in your details below to get started. Payment is processed securely via PayMongo.</p>
    </div>

    <livewire:public.registration-form :selected-plan-id="$selectedPlanId" />

    <p class="text-sm text-gray-500 mt-6">
        Already a member?
        <a href="/login" class="text-gray-900 underline">Sign in here</a>
    </p>
</div>

@endsection
