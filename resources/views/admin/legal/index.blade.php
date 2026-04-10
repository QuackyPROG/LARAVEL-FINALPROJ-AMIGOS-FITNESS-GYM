@extends('layouts.admin')

@section('title', 'Legal Documents')

@section('content')

<div class="mb-6">
    <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-1">Compliance</p>
    <h1 class="text-xl font-semibold text-gray-900">Legal Documents</h1>
    <p class="text-sm text-gray-500 mt-1">Edit the membership agreements, T&C, and privacy disclosures presented to members during registration.</p>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border border-gray-200 rounded-md overflow-hidden mb-4">
    <table class="w-full text-sm">
        <thead class="border-b border-gray-200 bg-gray-50">
            <tr>
                <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Document</th>
                <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Version</th>
                <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Last Updated</th>
                <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Action</th>
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
                <tr class="border-b border-gray-100">
                    <td class="py-3 px-4 text-gray-700">
                        <p class="font-medium text-gray-900">{{ $doc['title'] }}</p>
                    </td>
                    <td class="py-3 px-4">
                        @if($needsReview)
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">NEEDS REVIEW v{{ $doc['version'] }}</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">CURRENT v{{ $doc['version'] }}</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-500">
                        {{ $doc['updated_at'] ? \Carbon\Carbon::parse($doc['updated_at'])->format('M j, Y') : '—' }}
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.legal.edit', $doc['slug']) }}" class="text-sm text-gray-700 underline">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="bg-gray-50 border border-gray-200 rounded-md p-4 text-sm text-gray-600">
    <p>
        <strong>Important:</strong> Every save increments the document version. Members who registered under a previous version have their signed copy preserved. Review documents regularly to ensure legal compliance.
    </p>
</div>

@endsection
