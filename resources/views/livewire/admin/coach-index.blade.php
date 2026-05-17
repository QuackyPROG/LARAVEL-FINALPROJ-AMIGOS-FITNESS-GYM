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
            .gold-gradient-bg {
                background-size: 200% 200%;
                animation: pan-gradient 4s ease infinite;
            }
            @keyframes pan-gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            /* Custom Scrollbar for Dark/Gold Theme */
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.05);
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(251, 191, 36, 0.4);
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(251, 191, 36, 0.8);
            }
        </style>
        
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
            <div class="relative w-full max-w-2xl mx-auto group">
                <div class="absolute -inset-[1.5px] bg-gradient-to-r from-amber-300 via-yellow-600 to-amber-400 rounded-2xl gold-gradient-bg opacity-80 blur-[2px] transition-opacity duration-500"></div>
                
                <div class="relative bg-[#000000] rounded-2xl shadow-[0_0_40px_rgba(0,0,0,0.5)] p-8 w-full max-h-[90vh] overflow-y-auto custom-scrollbar">
                    
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
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-semibold text-[#a0a0a0] uppercase tracking-wider mb-1.5 ml-1">Coach Name</label>
                                    <input type="text" wire:model="name" placeholder="e.g. John Smith"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-[#707070] focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                                    @error('name') <span class="block mt-1 ml-1 text-xs text-red-400">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-[#a0a0a0] uppercase tracking-wider mb-1.5 ml-1">Photo (Optional)</label>
                                    <input id="coachPhotoInput" type="file" accept="image/*"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-white placeholder-[#707070] focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-500/20 file:text-amber-400 hover:file:bg-amber-500/30 cursor-pointer">
                                    <input id="photoCropped" type="hidden" wire:model="photoCropped">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-[#a0a0a0] uppercase tracking-wider mb-1.5 ml-1">Bio</label>
                                    <textarea wire:model="bio" rows="3" placeholder="Share expertise and experience..."
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-[#707070] focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner custom-scrollbar"></textarea>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <label class="block text-xs font-semibold text-[#a0a0a0] uppercase tracking-wider mb-1.5 ml-1">Specializations (one per line)</label>
                                <textarea wire:model="specializationsRaw" placeholder="e.g. Strength Training&#10;Yoga&#10;Cardio"
                                    class="flex-1 w-full h-full min-h-[150px] resize-none bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-[#707070] focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner custom-scrollbar"></textarea>
                            </div>
                            
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 pt-6 mt-8 border-t border-white/10">
                            <button type="button" wire:click="$set('showForm', false)" class="px-5 py-2.5 text-sm font-semibold text-gray-300 transition-all border rounded-lg bg-white/5 hover:bg-white/10 border-white/10">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-gray-900 transition-all transform rounded-lg bg-gradient-to-r from-amber-400 to-yellow-600 hover:from-amber-500 hover:to-yellow-700 shadow-[0_0_20px_rgba(251,191,36,0.2)] hover:shadow-[0_0_25px_rgba(251,191,36,0.4)] hover:-translate-y-0.5">
                                {{ $editingId ? 'Update Coach' : 'Add Coach' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @once
        @push('scripts')
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                let cropper;
                const input = document.getElementById('coachPhotoInput');
                const hidden = document.getElementById('photoCropped');

                // create modal elements
                const modal = document.createElement('div');
                modal.id = 'coachCropModal';
                modal.style = 'display:none;position:fixed;inset:0;z-index:60;align-items:center;justify-content:center;background:rgba(0,0,0,0.7);';
                modal.innerHTML = `
                    <div style="max-width:900px;width:90%;background:#0b0b0b;padding:18px;border-radius:12px;">
                        <div style="margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;">
                            <strong style="color:#fff">Crop Photo</strong>
                            <button id="coachCropCancel" style="background:transparent;border:0;color:#ccc;font-weight:bold;">Cancel</button>
                        </div>
                        <div style="width:100%;max-height:70vh;overflow:auto;"><img id="coachCropImage" style="max-width:100%;display:block;margin:0 auto;max-height:60vh;"/></div>
                        <div style="margin-top:12px;display:flex;gap:8px;justify-content:flex-end;">
                            <button id="coachCropBtn" style="background:#f59e0b;border:0;padding:8px 14px;border-radius:8px;font-weight:700;">Crop & Use</button>
                        </div>
                    </div>`;
                document.body.appendChild(modal);

                input?.addEventListener('change', function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function (evt) {
                        const img = document.getElementById('coachCropImage');
                        img.src = evt.target.result;
                        modal.style.display = 'flex';
                        // init cropper
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(img, { aspectRatio: 1, viewMode: 1, background:false, autoCropArea: 1 });
                    };
                    reader.readAsDataURL(file);
                });

                document.getElementById('coachCropCancel')?.addEventListener('click', function () {
                    modal.style.display = 'none';
                    if (cropper) { cropper.destroy(); cropper = null; }
                });

                document.getElementById('coachCropBtn')?.addEventListener('click', function () {
                    if (!cropper) return;
                    const canvas = cropper.getCroppedCanvas({ width: 800, height: 800, imageSmoothingQuality: 'high' });
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                    hidden.value = dataUrl;
                    hidden.dispatchEvent(new Event('input', { bubbles: true }));
                    modal.style.display = 'none';
                    cropper.destroy(); cropper = null;
                    // clear original file to avoid double-upload
                    input.value = '';
                });
            });
            </script>
        @endpush
    @endonce

    @php
        $badgeStyles = [
            'text-purple-400 border-purple-400/50',
            'text-blue-400 border-blue-400/50',
            'text-green-400 border-green-400/50',
            'text-orange-400 border-orange-400/50',
            'text-pink-400 border-pink-400/50',
            'text-teal-400 border-teal-400/50',
            'text-red-400 border-red-400/50',
            'text-amber-400 border-amber-400/50',
            'text-cyan-400 border-cyan-400/50',
            'text-lime-400 border-lime-400/50',
        ];
    @endphp

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($coaches as $coach)
        <div class="relative flex flex-col h-[340px] transition-all duration-300 border border-white/10 shadow-xl bg-white/5 backdrop-blur-md rounded-2xl hover:bg-white/10 hover:border-white/20 group">
            
            <div class="p-5 rounded-t-2xl">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 overflow-hidden border-2 border-[#555555] rounded-full bg-[#333333] shadow-inner">
                            @if($coach->photo)
                                <img src="{{ asset('storage/'.$coach->photo) }}" alt="{{ $coach->name }}" class="object-cover w-full h-full">
                            @else
                                <svg class="w-8 h-8 text-[#9ca3af]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <h3 class="text-lg font-bold text-white truncate">{{ $coach->name }}</h3>
                            <div class="flex items-center gap-1.5 mt-1 text-sm text-[#a0a0a0]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#808080]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $coach->bookings_count }} bookings
                            </div>
                        </div>
                    </div>

                    <div class="relative flex-shrink-0 group/dropdown">
                        <button class="p-1.5 transition-all border border-transparent rounded-lg text-[#a0a0a0] hover:text-white hover:bg-white/5 focus:outline-none" title="More actions">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <circle cx="3" cy="10" r="1.5" fill="currentColor"/>
                                <circle cx="10" cy="10" r="1.5" fill="currentColor"/>
                                <circle cx="17" cy="10" r="1.5" fill="currentColor"/>
                            </svg>
                        </button>
                        
                        <div class="absolute right-0 z-[60] invisible w-40 transition-all duration-200 opacity-0 mt-1 shadow-2xl bg-black/80 backdrop-blur-md border border-white/10 rounded-xl group-hover/dropdown:opacity-100 group-hover/dropdown:visible">
                            <div class="p-1.5">
                                <button wire:click="openEdit({{ $coach->id }})" class="flex items-center w-full gap-2 px-3 py-2 text-sm text-white transition-colors rounded-lg hover:bg-white/10 hover:text-amber-400 text-left">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit Coach
                                </button>
                                <button wire:click="confirmDelete({{ $coach->id }})" class="flex items-center w-full gap-2 px-3 py-2 text-sm text-red-400 transition-colors rounded-lg hover:bg-red-500/20 hover:text-red-300 text-left mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($coach->bio)
                    <p class="mt-4 text-sm text-[#a0a0a0] line-clamp-2">{{ $coach->bio }}</p>
                @endif
            </div>

            @if($coach->specializations)
                <div class="flex-1 px-5 py-4 border-t border-white/10 bg-white/5 rounded-b-2xl overflow-y-auto custom-scrollbar">
                    <div class="flex flex-wrap gap-2 content-start">
                        @foreach($coach->specializations as $s)
                            @php
                                $colorIndex = abs(crc32(strtolower(trim($s)))) % count($badgeStyles);
                                $styleClass = $badgeStyles[$colorIndex];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold tracking-wide uppercase bg-transparent border rounded-md {{ $styleClass }}">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $s }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        @empty
        <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-12 text-center shadow-inner">
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-md">
            <div class="w-full max-w-sm p-6 mx-4 shadow-2xl bg-[#1a1a1a] border border-red-500/50 rounded-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-600 to-red-400"></div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 rounded-full bg-red-900/30 text-red-400 border border-red-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
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
    @endif
</div>