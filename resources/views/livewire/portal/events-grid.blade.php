<div>
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">Events</h1>
        <p class="text-sm text-gray-500">Upcoming gym events</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($events as $event)
        <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
            @if($event->cover_image)
                <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-32 object-cover">
            @else
                <div class="w-full h-32 bg-gray-100 flex items-center justify-center">
                    <span class="text-3xl font-bold text-gray-200">{{ strtoupper(substr($event->title,0,1)) }}</span>
                </div>
            @endif
            <div class="p-4">
                <p class="font-medium text-gray-900">{{ $event->title }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ $event->date->format('F j, Y \a\t g:i A') }}</p>
                @if($event->description)<p class="text-sm text-gray-400 mt-1">{{ $event->description }}</p>@endif
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white border border-gray-200 rounded-md p-8 text-center">
            <p class="text-sm text-gray-400">No upcoming events</p>
            <p class="text-xs text-gray-300 mt-0.5">Check back soon for upcoming gym events and activities</p>
        </div>
        @endforelse
    </div>
</div>
