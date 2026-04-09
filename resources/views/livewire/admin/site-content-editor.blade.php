<div>

    <div>
        <h1>Site Content</h1>
        <p>Edit the public-facing homepage content. Changes go live immediately.</p>
    </div>

    @if($saved)
        <div
            x-data
            x-init="setTimeout(() => $wire.set('saved', false), 4000)"
        >
            Content saved successfully. The public homepage now reflects your changes.
        </div>
    @endif

    <form wire:submit="save">

        <div>
            <h2>Hero Section</h2>

            <div>
                <label>Hero Title</label>
                <input
                    type="text"
                    wire:model="hero_title"
                    placeholder="Train Hard. Live Strong."
                >
                @error('hero_title')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Hero Subtitle</label>
                <textarea
                    wire:model="hero_subtitle"
                    rows="3"
                    placeholder="Join AmigosFitnessGym..."
                ></textarea>
                @error('hero_subtitle')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Hero Image</label>

                @if($hero_image_path)
                    <div>
                        <img
                            src="{{ asset('storage/' . $hero_image_path) }}"
                            alt="Current hero image"
                        >
                        <p>Current image. Upload a new one to replace it.</p>
                    </div>
                @endif

                <input
                    type="file"
                    wire:model="hero_image_upload"
                    accept="image/*"
                >
                @error('hero_image_upload')
                    <p>{{ $message }}</p>
                @enderror

                <div wire:loading wire:target="hero_image_upload">
                    Uploading...
                </div>
            </div>
        </div>

        <div>
            <h2>Gym Information</h2>

            <div>
                <label>Gym Hours</label>
                <input
                    type="text"
                    wire:model="gym_hours"
                    placeholder="Mon–Fri: 5:00 AM – 10:00 PM | Sat–Sun: 6:00 AM – 8:00 PM"
                >
                @error('gym_hours')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Address</label>
                <input
                    type="text"
                    wire:model="gym_address"
                    placeholder="123 Fitness Street, Makati City"
                >
                @error('gym_address')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Phone Number</label>
                <input
                    type="text"
                    wire:model="gym_phone"
                    placeholder="+63 900 000 0000"
                >
                @error('gym_phone')
                    <p>{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <button
                type="submit"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="save">Save Changes</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
            <a href="{{ route('admin.dashboard') }}">Cancel</a>
        </div>

    </form>
</div>
