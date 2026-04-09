<div>
    <div>
        <h1>Class Schedule</h1>
        <p>Weekly recurring classes</p>
    </div>

    <div>
        @foreach($days as $day)
        <div>
            <div>
                <p>{{ $day }}</p>
            </div>
            <div>
                @forelse($schedules->get($day, collect()) as $class)
                <div>
                    <p>{{ $class->name }}</p>
                    <p>{{ $class->time }}</p>
                    @if($class->coach)<p>{{ $class->coach->name }}</p>@endif
                    <p>Cap: {{ $class->capacity }}</p>
                </div>
                @empty
                <div>
                    <p>No classes</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>
