@extends('layouts.admin')

@section('title', 'Add Walk-in Member')

@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.members.index') }}" class="text-sm text-gray-400 underline hover:text-white transition-colors">← Back</a>
        <div>
            <h1 class="text-3xl font-bold text-white">Add Walk-in Member</h1>
            <p class="text-sm text-gray-300 mt-0.5">Register a new physical member to the gym</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-900/20 border border-red-700 text-red-300 text-sm px-4 py-3 rounded-md mb-4">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif

    <div class="bg-dark-card border border-gray-600 rounded-md p-6">
        <form method="POST" action="{{ route('admin.members.store') }}">
            @csrf
            <div class="flex flex-col gap-1 mb-4">
                <label class="text-sm font-medium text-gray-300">Full Name</label>
                <input name="name" value="{{ old('name') }}" required class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400 focus:ring-1 focus:ring-amber-400 focus:outline-none">
            </div>
            <div class="flex flex-col gap-1 mb-4">
                <label class="text-sm font-medium text-gray-300">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" required class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400 focus:ring-1 focus:ring-amber-400 focus:outline-none">
            </div>
            <div class="flex flex-col gap-1 mb-6">
                <label class="text-sm font-medium text-gray-300">Membership Plan</label>
                <select name="plan_id" required class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white focus:ring-1 focus:ring-amber-400 focus:outline-none">
                    <option value="" class="bg-dark-page text-white">Select plan…</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" class="bg-dark-page text-white" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} — ₱{{ number_format($plan->price, 0) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md">Create Member</button>
        </form>
    </div>
</div>
@endsection
