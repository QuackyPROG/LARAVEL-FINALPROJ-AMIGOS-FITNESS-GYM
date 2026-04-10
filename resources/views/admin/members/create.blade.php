@extends('layouts.admin')

@section('title', 'Add Walk-in Member')

@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.members.index') }}" class="text-sm text-gray-500 underline">← Back</a>
        <h1 class="text-xl font-semibold text-gray-900">Add Walk-in Member</h1>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-md mb-4">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-md p-6">
        <form method="POST" action="{{ route('admin.members.store') }}">
            @csrf
            <div class="flex flex-col gap-1 mb-4">
                <label class="text-sm font-medium text-gray-700">Full Name</label>
                <input name="name" value="{{ old('name') }}" required class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
            </div>
            <div class="flex flex-col gap-1 mb-4">
                <label class="text-sm font-medium text-gray-700">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" required class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
            </div>
            <div class="flex flex-col gap-1 mb-6">
                <label class="text-sm font-medium text-gray-700">Membership Plan</label>
                <select name="plan_id" required class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                    <option value="">Select plan…</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} — ₱{{ number_format($plan->price, 0) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">Create Member</button>
        </form>
    </div>
</div>
@endsection
