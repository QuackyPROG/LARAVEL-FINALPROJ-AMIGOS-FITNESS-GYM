<div>
    <x-admin-splash target="save" :successMessage="$saved ? 'Content saved successfully. The public homepage now reflects your changes.' : null" resetFlag="saved" />

    <form wire:submit="save">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Site Content</h1>
                <p class="text-gray-300">Edit the public-facing homepage content. Changes go live immediately.</p>
            </div>
            
            <div wire:ignore class="text-right" x-data="{
                date: '',
                time: '',
                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);
                },
                updateClock() {
                    const now = new Date();
                    this.date = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    this.time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                }
            }">
                <div class="text-amber-400 text-sm font-medium tracking-wide uppercase" x-text="date"></div>
                <div class="text-white text-4xl font-extrabold tracking-tight mt-0.5" x-text="time"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl shadow-xl p-6 transition-all hover:border-white/20">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-6 bg-amber-500 rounded-full"></div>
                    <h2 class="text-sm font-semibold text-gray-300 uppercase tracking-widest">Hero Section</h2>
                </div>

                <div class="space-y-5">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-400 uppercase">Hero Title</label>
                        <input
                            type="text"
                            wire:model="hero_title"
                            placeholder="Train Hard. Live Strong."
                            class="border border-white/10 rounded-xl px-4 py-3 text-sm w-full bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all"
                        >
                        @error('hero_title')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-400 uppercase">Hero Subtitle</label>
                        <textarea
                            wire:model="hero_subtitle"
                            rows="4"
                            placeholder="Join AmigosFitnessGym..."
                            class="border border-white/10 rounded-xl px-4 py-3 text-sm w-full bg-white/5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 custom-scrollbar transition-all"
                        ></textarea>
                        @error('hero_subtitle')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-400 uppercase">Hero Image</label>

                        @if($hero_image_path)
                            <div class="relative group w-full h-40 mb-2 overflow-hidden rounded-xl border border-white/10">
                                <img
                                    src="{{ asset('storage/' . $hero_image_path) }}"
                                    alt="Current hero image"
                                    class="w-full h-full object-cover"
                                >
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-xs font-medium">Current Image</span>
                                </div>
                            </div>
                        @endif

                        <div class="relative">
                            <input
                                type="file"
                                wire:model="hero_image_upload"
                                accept="image/*"
                                class="border border-dashed border-white/20 rounded-xl px-3 py-4 text-sm w-full bg-white/5 text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-amber-500/20 file:text-amber-400 hover:file:bg-amber-500/30 cursor-pointer"
                            >
                        </div>
                        @error('hero_image_upload')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror

                        <div wire:loading wire:target="hero_image_upload" class="flex items-center gap-2 text-xs text-amber-400 mt-2">
                            <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Uploading image...
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl shadow-xl p-6 transition-all hover:border-white/20">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-6 bg-amber-500 rounded-full"></div>
                    <h2 class="text-sm font-semibold text-gray-300 uppercase tracking-widest">Gym Information</h2>
                </div>

                <div class="space-y-5">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-400 uppercase">Gym Hours</label>
                        <input
                            type="text"
                            wire:model="gym_hours"
                            placeholder="Mon–Fri: 5:00 AM – 10:00 PM"
                            class="border border-white/10 bg-white/5 rounded-xl px-4 py-3 text-sm w-full text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all"
                        >
                        @error('gym_hours')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-400 uppercase">Address</label>
                        <input
                            type="text"
                            wire:model="gym_address"
                            placeholder="123 Fitness Street, Makati City"
                            class="border border-white/10 bg-white/5 rounded-xl px-4 py-3 text-sm w-full text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all"
                        >
                        @error('gym_address')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-400 uppercase">Phone Number</label>
                        <input
                            type="text"
                            wire:model="gym_phone"
                            placeholder="+63 900 000 0000"
                            class="border border-white/10 bg-white/5 rounded-xl px-4 py-3 text-sm w-full text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all"
                        >
                        @error('gym_phone')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-black bg-gradient-to-r from-amber-300 via-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-700 transition-all shadow-lg shadow-amber-500/20 active:scale-95 disabled:opacity-50 disabled:pointer-events-none"
            >
                <span wire:loading.remove wire:target="save">Save Changes</span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>
</div>