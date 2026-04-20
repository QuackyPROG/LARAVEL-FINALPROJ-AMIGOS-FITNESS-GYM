@extends('layouts.admin')

@section('title', 'Legal Documents')

@section('content')

<div class="mb-8">
    <h1 class="text-3xl font-bold text-white mb-2">Legal Documents</h1>
    <p class="text-gray-300">Edit the membership agreements, T&C, and privacy disclosures presented to members during registration.</p>
</div>

@if(session('success'))
    <div class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-dark-card border border-gray-600 rounded-md overflow-hidden mb-4">
    <table class="w-full text-sm">
        <thead class="border-b border-gray-600 bg-dark-card">
            <tr>
                <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Document</th>
                <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Version</th>
                <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Last Updated</th>
                <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Action</th>
            </tr>
        </thead>
        <tbody class="bg-dark-card">
            @foreach($documents as $doc)
                @php
                    $daysSince = $doc['updated_at']
                        ? \Carbon\Carbon::parse($doc['updated_at'])->diffInDays(now())
                        : null;
                    $needsReview = $daysSince !== null && $daysSince > 180;
                @endphp
                <tr class="border-b border-gray-600 hover:bg-gray-700 transition-colors">
                    <td class="py-3 px-4 text-white">
                        <p class="font-medium text-white">{{ $doc['title'] }}</p>
                    </td>
                    <td class="py-3 px-4">
                        @if($needsReview)
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-yellow-900/20 text-yellow-300 border border-yellow-700">NEEDS REVIEW v{{ $doc['version'] }}</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-900/20 text-green-300 border border-green-700">CURRENT v{{ $doc['version'] }}</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-400">
                        {{ $doc['updated_at'] ? \Carbon\Carbon::parse($doc['updated_at'])->format('M j, Y') : '—' }}
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.legal.edit', $doc['slug']) }}" class="text-sm text-gray-300 underline hover:text-white transition-colors">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="bg-blue-900/20 border border-blue-700 rounded-md p-4 text-sm text-blue-300">
    <p>
        <strong>Important:</strong> Every save increments the document version. Members who registered under a previous version have their signed copy preserved. Review documents regularly to ensure legal compliance.
    </p>
</div>

@endsection
