<div>
    <div>
        <h1>Events</h1>
        <p>Upcoming gym events</p>
    </div>

    <div>
        @forelse($events as $event)
        <div>
            @if($event->cover_image)
                <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}">
            @else
                <div>
                    <span>{{ strtoupper(substr($event->title,0,1)) }}</span>
                </div>
            @endif
            <div>
                <p>{{ $event->title }}</p>
                <p>{{ $event->date->format('F j, Y \a\t g:i A') }}</p>
                @if($event->description)<p>{{ $event->description }}</p>@endif
            </div>
        </div>
        @empty
        <div>
            <p>No upcoming events</p>
            <p>Check back soon for upcoming gym events and activities</p>
        </div>
        @endforelse
    </div>
</div>
