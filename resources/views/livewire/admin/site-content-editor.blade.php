<div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Site Content</h1>
        <p class="text-gray-300">Edit the public-facing homepage content. Changes go live immediately.</p>
    </div>

    @if($saved)
        <div
            x-data
            x-init="setTimeout(() => $wire.set('saved', false), 4000)"
            class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4"
        >
            Content saved successfully. The public homepage now reflects your changes.
        </div>
    @endif

    <form wire:submit="save">

        <div class="bg-dark-card border border-gray-600 rounded-md p-5 mb-4">
            <h2 class="text-sm font-semibold text-gray-300 uppercase tracking-wide mb-4">Hero Section</h2>

            <div class="flex flex-col gap-1 mb-4">
                <label class="text-sm font-medium text-gray-300">Hero Title</label>
                <input
                    type="text"
                    wire:model="hero_title"
                    placeholder="Train Hard. Live Strong."
                    class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400"
                >
                @error('hero_title')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-1 mb-4">
                <label class="text-sm font-medium text-gray-300">Hero Subtitle</label>
                <textarea
                    wire:model="hero_subtitle"
                    rows="3"
                    placeholder="Join AmigosFitnessGym..."
                    class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400"
                ></textarea>
                @error('hero_subtitle')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Hero Image</label>

                @if($hero_image_path)
                    <div class="mb-3">
                        <img
                            src="{{ asset('storage/' . $hero_image_path) }}"
                            alt="Current hero image"
                            class="w-32 h-20 object-cover rounded border border-gray-600"
                        >
                        <p class="text-xs text-gray-400 mt-1">Current image. Upload a new one to replace it.</p>
                    </div>
                @endif

                <input
                    type="file"
                    wire:model="hero_image_upload"
                    accept="image/*"
                    class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white"
                >
                @error('hero_image_upload')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                @enderror

                <div wire:loading wire:target="hero_image_upload" class="text-sm text-gray-400 mt-1">
                    Uploading...
                </div>
            </div>
        </div>

        <div class="bg-dark-card border border-gray-600 rounded-md p-5 mb-4">
            <h2 class="text-sm font-semibold text-gray-300 uppercase tracking-wide mb-4">Gym Information</h2>

            <div class="flex flex-col gap-1 mb-4">
                <label class="text-sm font-medium text-gray-300">Gym Hours</label>
                <input
                    type="text"
                    wire:model="gym_hours"
                    placeholder="Mon–Fri: 5:00 AM – 10:00 PM | Sat–Sun: 6:00 AM – 8:00 PM"
                    class="border border-gray-600 bg-dark-page text-white placeholder-gray-400 rounded-md px-3 py-2 text-sm w-full"
                >
                @error('gym_hours')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-1 mb-4">
                <label class="text-sm font-medium text-gray-300">Address</label>
                <input
                    type="text"
                    wire:model="gym_address"
                    placeholder="123 Fitness Street, Makati City"
                    class="border border-gray-600 bg-dark-page text-white placeholder-gray-400 rounded-md px-3 py-2 text-sm w-full"
                >
                @error('gym_address')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Phone Number</label>
                <input
                    type="text"
                    wire:model="gym_phone"
                    placeholder="+63 900 000 0000"
                    class="border border-gray-600 bg-dark-page text-white placeholder-gray-400 rounded-md px-3 py-2 text-sm w-full"
                >
                @error('gym_phone')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md"
            >
                <span wire:loading.remove wire:target="save">Save Changes</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 underline">Cancel</a>
        </div>

    </form>
</div>
