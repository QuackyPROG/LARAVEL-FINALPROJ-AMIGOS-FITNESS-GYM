<div>
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">Class Schedule</h1>
        <p class="text-sm text-gray-500">Weekly recurring classes</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($days as $day)
        <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
            <div class="px-3 py-2 border-b border-gray-100 bg-gray-50">
                <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide">{{ $day }}</p>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($schedules->get($day, collect()) as $class)
                <div class="px-3 py-3">
                    <p class="text-sm font-medium text-gray-900">{{ $class->name }}</p>
                    <p class="text-xs text-gray-500">{{ $class->time }}</p>
                    @if($class->coach)<p class="text-xs text-gray-400">{{ $class->coach->name }}</p>@endif
                    <p class="text-xs text-gray-400">Cap: {{ $class->capacity }}</p>
                </div>
                @empty
                <div class="px-3 py-4 text-center">
                    <p class="text-xs text-gray-300">No classes</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>
