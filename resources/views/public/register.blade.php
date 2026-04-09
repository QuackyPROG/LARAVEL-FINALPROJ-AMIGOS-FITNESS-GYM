@extends('layouts.public')

@section('title', 'Become a Member')

@section('content')

<div>
    <div>
        <p>Registration</p>
        <h1>Become a Member</h1>
        <p>Fill in your details below to get started. Payment is processed securely via PayMongo.</p>
    </div>

    <livewire:public.registration-form :selected-plan-id="$selectedPlanId" />

    <p>
        Already a member?
        <a href="/login">Sign in here</a>
    </p>
</div>

@endsection
