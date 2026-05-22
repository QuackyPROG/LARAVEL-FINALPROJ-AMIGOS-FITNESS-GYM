<div>
    <div wire:loading.flex wire:target="save, executeDelete" class="fixed inset-0 z-[100] flex-col items-center justify-center bg-black/80 backdrop-blur-md">
        <svg class="w-16 h-16 text-amber-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <div class="mt-6 text-2xl font-bold text-white animate-pulse">Processing...</div>
        <p class="text-sm text-[#a0a0a0] mt-2">Please wait a moment</p>
    </div>

    @if(session('success'))
        <style>
            .green-gradient-bg {
                background-size: 200% 200%;
                animation: pan-gradient 4s ease infinite;
            }
            @keyframes pan-gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
        </style>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2500)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300 delay-100"
                 x-transition:enter-start="opacity-0 scale-50"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative w-full max-w-sm mx-auto group">
                 <div class="absolute -inset-[1.5px] bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 rounded-2xl green-gradient-bg opacity-80 blur-[2px] transition-opacity duration-500"></div>
                 <div class="relative flex flex-col items-center p-8 bg-[#1e1e1e] rounded-2xl shadow-[0_0_40px_rgba(16,185,129,0.3)] w-full text-center">
                    <div class="flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-green-500/20 text-green-400 border-2 border-green-500/50 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="mb-2 text-2xl font-bold tracking-wide text-green-400">Success!</div>
                    <p class="text-[#a0a0a0]">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="mb-2 text-3xl font-bold text-white">Coaches</h1>
            <p class="text-gray-300">Manage coaching staff and their specializations</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-2 px-4 py-2 mt-2 text-sm font-bold text-gray-900 transition-all duration-300 rounded-lg shadow-lg bg-gradient-to-r from-amber-400 via-yellow-500 to-amber-500 hover:from-amber-300 hover:via-yellow-400 hover:to-amber-400 shadow-yellow-500/20 hover:shadow-yellow-500/40 hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Coach
        </button>
    </div>

    @if($showForm)
        <style>
            .gold-gradient-bg { background-size: 200% 200%; animation: pan-gradient 4s ease infinite; }
            @keyframes pan-gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(251,191,36,0.4); border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(251,191,36,0.8); }
        </style>

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
            <div class="relative w-full max-w-2xl mx-auto group">
                <div class="absolute -inset-[1.5px] bg-gradient-to-r from-amber-300 via-yellow-600 to-amber-400 rounded-2xl gold-gradient-bg opacity-80 blur-[2px] transition-opacity duration-500"></div>

                <div class="relative bg-[#000000] rounded-2xl shadow-[0_0_40px_rgba(0,0,0,0.5)] p-8 w-full max-h-[90vh] overflow-y-auto custom-scrollbar"
                     x-data="{
                        cropper: null,
                        cropModalOpen: false,
                        avatarPreview: '{{ $editingId && $photoCropped ? $photoCropped : '' }}',
                        initCropper() {
                            const input = this.$refs.photoInput;
                            if (!input) return;
                            input.addEventListener('change', (e) => {
                                const file = e.target.files && e.target.files[0];
                                if (!file) return;
                                const reader = new FileReader();
                                reader.onload = (evt) => {
                                    this.$refs.cropImage.src = evt.target.result;
                                    this.cropModalOpen = true;
                                    this.$nextTick(() => {
                                        if (this.cropper) this.cropper.destroy();
                                        this.cropper = new Cropper(this.$refs.cropImage, {
                                            aspectRatio: 1,
                                            viewMode: 1,
                                            background: false,
                                            autoCropArea: 1
                                        });
                                    });
                                };
                                reader.readAsDataURL(file);
                            });
                        },
                        cropAndUse() {
                            if (!this.cropper) return;
                            const canvas = this.cropper.getCroppedCanvas({ width: 800, height: 800, imageSmoothingQuality: 'high' });
                            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                            this.avatarPreview = dataUrl;
                            $wire.set('photoCropped', dataUrl);
                            this.cropModalOpen = false;
                            this.cropper.destroy();
                            this.cropper = null;
                            this.$refs.photoInput.value = '';
                        }
                     }"
                     x-init="initCropper()">

                    {{-- Crop modal overlay --}}
                    <div x-show="cropModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="display:none;">
                        <div class="bg-[#0b0b0b] rounded-xl p-5 w-full max-w-2xl">
                            <div class="flex justify-between items-center mb-3">
                                <strong class="text-white">Crop Photo</strong>
                                <button @click="cropModalOpen = false; if(cropper){cropper.destroy();cropper=null;}" class="text-gray-400 hover:text-white font-bold px-2">Cancel</button>
                            </div>
                            <div class="w-full max-h-[60vh] overflow-auto flex items-center justify-center">
                                <img x-ref="cropImage" style="max-width:100%;display:block;max-height:55vh;" />
                            </div>
                            <div class="mt-3 flex justify-end">
                                <button @click="cropAndUse()" class="bg-amber-500 hover:bg-amber-400 text-black font-bold px-5 py-2 rounded-lg">Crop & Use</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center mb-8 text-left">
                        <div class="flex-shrink-0 mr-4 bg-gradient-to-br from-amber-400/20 to-yellow-600/20 border border-amber-500/30 text-amber-400 p-3.5 rounded-full shadow-[0_0_20px_rgba(251,191,36,0.15)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-extrabold tracking-wide text-white">{{ $editingId ? 'Edit Coach' : 'Add Coach' }}</h2>
                            <p class="mt-1 text-sm text-[#a0a0a0]">{{ $editingId ? 'Update coach details' : 'Register a new coaching staff member' }}</p>
                        </div>
                    </div>

                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                            {{-- Left col: avatar + upload --}}
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-amber-500/40 bg-amber-500/20 flex items-center justify-center shadow-[0_0_15px_rgba(251,191,36,0.15)]">
                                    <template x-if="avatarPreview">
                                        <img :src="avatarPreview" class="w-full h-full object-cover" />
                                    </template>
                                    <template x-if="!avatarPreview">
                                        @if($editingId)
                                            @php $coach = \App\Models\Coach::find($editingId); @endphp
                                            @if($coach?->photo)
                                                <img src="{{ asset('storage/'.$coach->photo) }}" class="w-full h-full object-cover" />
                                            @else
                                                <span class="text-amber-400 font-bold text-xl">{{ strtoupper(substr($name ?: 'C', 0, 1)) }}</span>
                                            @endif
                                        @else
                                            <span class="text-amber-400 font-bold text-xl" x-text="'{{ substr($name ?: '', 0, 1) }}'.toUpperCase() || 'C'"></span>
                                        @endif
                                    </template>
                                </div>
                                <label class="text-xs text-amber-400 underline cursor-pointer hover:text-amber-300 transition-colors">
                                    Upload Photo
                                    <input x-ref="photoInput" type="file" accept="image/*" class="hidden">
                                </label>
                                <input type="hidden" wire:model="photoCropped">
                                <p class="text-[10px] text-gray-500 text-center">Square image recommended.<br>Will be cropped to circle.</p>
                            </div>

                            {{-- Right col: name, bio, specs --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-[#a0a0a0] uppercase tracking-wider mb-1.5">Coach Name</label>
                                    <input type="text" wire:model="name" placeholder="e.g. John Smith"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-[#707070] focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                                    @error('name') <span class="block mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-[#a0a0a0] uppercase tracking-wider mb-1.5">Bio</label>
                                    <textarea wire:model="bio" rows="3" placeholder="Share expertise and experience..."
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-[#707070] focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner custom-scrollbar resize-none"></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-[#a0a0a0] uppercase tracking-wider mb-1.5">Specializations</label>
                                    <textarea wire:model="specializationsRaw" rows="3" placeholder="e.g. Strength Training&#10;Yoga&#10;Cardio"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-[#707070] focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner custom-scrollbar resize-none"></textarea>
                                    <p class="text-[10px] text-gray-500 mt-1">One per line</p>
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-white/10">
                            <button type="button" wire:click="$set('showForm', false)" class="px-5 py-2.5 text-sm font-semibold text-gray-300 transition-all border rounded-lg bg-white/5 hover:bg-white/10 border-white/10">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-gray-900 transition-all transform rounded-lg bg-gradient-to-r from-amber-400 to-yellow-600 hover:from-amber-500 hover:to-yellow-700 shadow-[0_0_20px_rgba(251,191,36,0.2)] hover:shadow-[0_0_25px_rgba(251,191,36,0.4)] hover:-translate-y-0.5">
                                {{ $editingId ? 'Update Coach →' : 'Save Coach →' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @php
        $badgeStyles = [
            'text-purple-400 border-purple-400/50 bg-purple-400/10',
            'text-blue-400 border-blue-400/50 bg-blue-400/10',
            'text-green-400 border-green-400/50 bg-green-400/10',
            'text-orange-400 border-orange-400/50 bg-orange-400/10',
            'text-pink-400 border-pink-400/50 bg-pink-400/10',
            'text-teal-400 border-teal-400/50 bg-teal-400/10',
            'text-red-400 border-red-400/50 bg-red-400/10',
            'text-amber-400 border-amber-400/50 bg-amber-400/10',
            'text-cyan-400 border-cyan-400/50 bg-cyan-400/10',
            'text-lime-400 border-lime-400/50 bg-lime-400/10',
        ];
    @endphp

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($coaches as $coach)
        <div class="flex flex-col bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-xl hover:border-white/20 transition-all duration-300 group">

            {{-- Avatar (centered) --}}
            <div class="flex flex-col items-center mb-4">
                <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-amber-500/40 bg-amber-500/20 flex items-center justify-center shadow-[0_0_15px_rgba(251,191,36,0.1)] mb-3">
                    @if($coach->photo)
                        <img src="{{ asset('storage/'.$coach->photo) }}" alt="{{ $coach->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-amber-400 font-bold text-2xl">{{ strtoupper(substr($coach->name, 0, 1)) }}</span>
                    @endif
                </div>
                <h3 class="text-lg font-bold text-white text-center">{{ $coach->name }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $coach->bookings_count }} {{ Str::plural('booking', $coach->bookings_count) }}</p>
            </div>

            {{-- Specialization badges (first 2 + overflow) --}}
            @if($coach->specializations && count($coach->specializations) > 0)
                <div class="flex flex-wrap gap-1.5 justify-center mb-4 flex-1">
                    @foreach(array_slice($coach->specializations, 0, 2) as $s)
                        @php
                            $colorIndex = abs(crc32(strtolower(trim($s)))) % count($badgeStyles);
                            $styleClass = $badgeStyles[$colorIndex];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold tracking-wide uppercase border rounded-md {{ $styleClass }}">{{ $s }}</span>
                    @endforeach
                    @if(count($coach->specializations) > 2)
                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold text-gray-400 border border-white/10 rounded-md bg-white/5">+{{ count($coach->specializations) - 2 }} more</span>
                    @endif
                </div>
            @else
                <div class="flex-1"></div>
            @endif

            {{-- Edit + Delete buttons --}}
            <div class="flex items-center justify-center gap-2 pt-4 border-t border-white/10">
                <button wire:click="openEdit({{ $coach->id }})" class="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-amber-400 hover:text-amber-300 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 rounded-xl transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit
                </button>
                <button wire:click="confirmDelete({{ $coach->id }})" class="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-red-400 hover:text-red-300 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 rounded-xl transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-1 sm:col-span-2 lg:col-span-3 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-12 text-center shadow-inner">
            <div class="flex justify-center mb-4 text-[#707070]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <p class="text-base font-medium text-white">No coaches registered yet</p>
            <p class="mt-1 text-sm text-[#a0a0a0]">Add your first coach to enable session bookings</p>
        </div>
        @endforelse
    </div>

    @if($showDeleteModal && $selectedCoach)
        <style>
            .spin-bg-red { background: conic-gradient(from 0deg, #ef4444, #7f1d1d, #fecaca, #7f1d1d, #ef4444); }
        </style>
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-md p-4">
            <div class="relative w-full max-w-sm mx-auto overflow-hidden rounded-2xl p-[2px] bg-[#1a1a1a]">
                <div class="absolute top-1/2 left-1/2 w-[250%] h-[250%] origin-center -translate-x-1/2 -translate-y-1/2 animate-[spin_8s_linear_infinite] opacity-100 spin-bg-red"></div>
                <div class="relative z-10 bg-[#0a0a0a] rounded-xl shadow-2xl p-6 w-full h-full isolate">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 rounded-full bg-red-900/30 text-red-400 border border-red-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Delete Coach</h2>
                    </div>
                    <p class="mb-8 text-sm text-[#a0a0a0]">Are you sure you want to permanently delete <strong class="text-white">{{ $selectedCoach->name }}</strong>? This action cannot be undone.</p>
                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showDeleteModal', false)" class="px-5 py-2.5 text-sm font-semibold text-[#a0a0a0] transition-colors border border-[#404040] rounded-lg hover:bg-[#252525] hover:text-white">Cancel</button>
                        <button wire:click="executeDelete" class="px-5 py-2.5 text-sm font-bold text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700 shadow-lg shadow-red-600/20">Delete Coach</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
