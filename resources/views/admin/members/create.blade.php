@extends('layouts.admin')

@section('title', 'Add Walk-in Member')

@section('content')
<div>
    <div>
        <a href="{{ route('admin.members.index') }}">← Back</a>
        <h1>Add Walk-in Member</h1>
    </div>

    @if($errors->any())
        <div>
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.members.store') }}">
        @csrf
        <div>
            <label>Full Name</label>
            <input name="name" value="{{ old('name') }}" required>
        </div>
        <div>
            <label>Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required>
        </div>
        <div>
            <label>Membership Plan</label>
            <select name="plan_id" required>
                <option value="">Select plan…</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} — ₱{{ number_format($plan->price, 0) }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit">Create Member</button>
    </form>
</div>
@endsection
