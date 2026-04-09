@extends('layouts.admin')

@section('title', 'Legal Documents')

@section('content')

<div>
    <p>Compliance</p>
    <h1>Legal Documents</h1>
    <p>Edit the membership agreements, T&C, and privacy disclosures presented to members during registration.</p>
</div>

@if(session('success'))
    <div>
        {{ session('success') }}
    </div>
@endif

<div>
    <table>
        <thead>
            <tr>
                <th>Document</th>
                <th>Version</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $doc)
                @php
                    $daysSince = $doc['updated_at']
                        ? \Carbon\Carbon::parse($doc['updated_at'])->diffInDays(now())
                        : null;
                    $needsReview = $daysSince !== null && $daysSince > 180;
                @endphp
                <tr>
                    <td>
                        <p>{{ $doc['title'] }}</p>
                    </td>
                    <td>
                        @if($needsReview)
                            <span>NEEDS REVIEW v{{ $doc['version'] }}</span>
                        @else
                            <span>CURRENT v{{ $doc['version'] }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $doc['updated_at'] ? \Carbon\Carbon::parse($doc['updated_at'])->format('M j, Y') : '—' }}
                    </td>
                    <td>
                        <a href="{{ route('admin.legal.edit', $doc['slug']) }}">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div>
    <p>
        <strong>Important:</strong> Every save increments the document version. Members who registered under a previous version have their signed copy preserved. Review documents regularly to ensure legal compliance.
    </p>
</div>

@endsection
