<div>
    <x-admin-splash target="save, toggleVisible, executeDelete" />
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Events</h1>
            <p class="text-gray-300">Manage gym events visible to members</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-2 px-4 py-2 mt-2 text-sm font-bold text-gray-900 transition-all duration-300 rounded-lg shadow-lg bg-gradient-to-r from-amber-400 via-yellow-500 to-amber-500 hover:from-amber-300 hover:via-yellow-400 hover:to-amber-400 shadow-yellow-500/20 hover:shadow-yellow-500/40 hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Event
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
    </style>
    
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-3xl mx-auto group">
            <div class="absolute -inset-[1.5px] bg-gradient-to-r from-amber-300 via-yellow-600 to-amber-400 rounded-2xl gold-gradient-bg opacity-80 blur-[2px] transition-opacity duration-500"></div>
            
            <div class="relative bg-[#000000] rounded-2xl shadow-[0_0_40px_rgba(0,0,0,0.5)] p-8 w-full">
                
                <div class="flex items-start justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 bg-gradient-to-br from-amber-400/20 to-yellow-600/20 border border-amber-500/30 text-amber-400 p-3.5 rounded-full shadow-[0_0_20px_rgba(251,191,36,0.15)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <h2 class="text-2xl font-extrabold text-white tracking-wide uppercase">{{ $editingId ? 'Edit Event' : 'New Event' }}</h2>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $editingId ? 'Update existing event details' : 'Broadcast a new event to your gym' }}</p>
                        </div>
                    </div>
                    <button wire:click="$set('showForm', false)" class="text-gray-500 hover:text-white transition-colors p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Left Column: Images, Title, Date --}}
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Cover Image</label>
                                <input id="eventCoverInput" type="file" accept="image/*"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner file:text-amber-400 file:bg-amber-500/20 hover:file:bg-amber-500/30 file:border-0 file:rounded-lg file:px-4 file:py-1 file:-ml-2 file:mr-4 file:font-bold file:text-xs file:cursor-pointer cursor-pointer">
                                <input id="coverImageCropped" type="hidden" wire:model="coverImageCropped">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Event Title</label>
                                <input type="text" wire:model="title" placeholder="e.g. Summer Fitness Challenge"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                                @error('title') <span class="text-xs text-red-400 mt-1 ml-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Date & Time</label>
                                <input type="datetime-local" wire:model="date" style="color-scheme: dark;"
                                    class="w-full bg-[#0a0a0a] hover:bg-[#111111] border border-white/10 hover:border-amber-500/50 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all shadow-inner cursor-pointer" required>
                                @error('date') <span class="text-xs text-red-400 mt-1 ml-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Right Column: Description & Visibility Toggle --}}
                        <div class="flex flex-col">
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Description</label>
                            <textarea wire:model="description" placeholder="Share details about this event..."
                                class="w-full flex-grow bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner min-h-[150px] md:min-h-0 mb-5"></textarea>
                            
                            {{-- Checkbox with Custom SVG Checkmark --}}
                            <div class="flex items-center gap-3 px-1 h-[46px]"> 
                                <div class="relative flex items-center">
                                    <input type="checkbox" wire:model="isVisible" id="vis" 
                                        class="peer h-5 w-5 cursor-pointer appearance-none rounded border-2 border-gray-500 bg-transparent checked:border-amber-500 focus:ring-0 focus:outline-none transition-all">
                                    
                                    <svg class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 text-amber-500 pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity duration-200" 
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <label for="vis" class="text-sm font-medium text-gray-300 cursor-pointer">Make event visible to members</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-white/10">
                        <button type="button" wire:click="$set('showForm', false)" class="px-6 py-2.5 text-sm font-semibold text-gray-300 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-gray-900 transition-all transform rounded-lg bg-gradient-to-r from-amber-400 to-yellow-600 hover:from-amber-500 hover:to-yellow-700 shadow-[0_0_20px_rgba(251,191,36,0.2)] hover:shadow-[0_0_25px_rgba(251,191,36,0.4)] hover:-translate-y-0.5">
                            {{ $editingId ? 'Update Event' : 'Create Event' }}
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
                let cropperEv;
                const inputEv = document.getElementById('eventCoverInput');
                const hiddenEv = document.getElementById('coverImageCropped');

                const modalEv = document.createElement('div');
                modalEv.id = 'eventCropModal';
                modalEv.style = 'display:none;position:fixed;inset:0;z-index:60;align-items:center;justify-content:center;background:rgba(0,0,0,0.7);';
                modalEv.innerHTML = `
                    <div style="max-width:1100px;width:94%;background:#0b0b0b;padding:18px;border-radius:12px;">
                        <div style="margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;">
                            <strong style="color:#fff">Crop Cover Image</strong>
                            <button id="eventCropCancel" style="background:transparent;border:0;color:#ccc;font-weight:bold;">Cancel</button>
                        </div>
                        <div style="width:100%;max-height:70vh;overflow:auto;"><img id="eventCropImage" style="max-width:100%;display:block;margin:0 auto;max-height:60vh;"/></div>
                        <div style="margin-top:12px;display:flex;gap:8px;justify-content:flex-end;">
                            <button id="eventCropBtn" style="background:#f59e0b;border:0;padding:8px 14px;border-radius:8px;font-weight:700;">Crop & Use</button>
                        </div>
                    </div>`;
                document.body.appendChild(modalEv);

                inputEv?.addEventListener('change', function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function (evt) {
                        const img = document.getElementById('eventCropImage');
                        img.src = evt.target.result;
                        modalEv.style.display = 'flex';
                        if (cropperEv) cropperEv.destroy();
                        cropperEv = new Cropper(img, { aspectRatio: 16/9, viewMode: 1, background:false, autoCropArea: 1 });
                    };
                    reader.readAsDataURL(file);
                });

                document.getElementById('eventCropCancel')?.addEventListener('click', function () {
                    modalEv.style.display = 'none';
                    if (cropperEv) { cropperEv.destroy(); cropperEv = null; }
                });

                document.getElementById('eventCropBtn')?.addEventListener('click', function () {
                    if (!cropperEv) return;
                    const canvas = cropperEv.getCroppedCanvas({ width: 1600, height: 900, imageSmoothingQuality: 'high' });
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                    hiddenEv.value = dataUrl;
                    hiddenEv.dispatchEvent(new Event('input', { bubbles: true }));
                    modalEv.style.display = 'none';
                    cropperEv.destroy(); cropperEv = null;
                    inputEv.value = '';
                });
            });
            </script>
        @endpush
    @endonce

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse($events as $event)
        <div class="flex flex-col overflow-hidden transition-all duration-300 border shadow-xl bg-white/5 backdrop-blur-md border-gray-600/50 rounded-xl hover:bg-white/10 hover:border-gray-500">
            
            {{-- Cover Image / Placeholder --}}
            <div class="relative flex items-center justify-center w-full overflow-hidden bg-[#111111] border-b border-gray-600/50 h-44 shrink-0">
                @if($event->cover_image)
                    <img src="{{ asset('storage/' . $event->cover_image) }}" class="object-cover w-full h-full transition-transform duration-500 hover:scale-105" alt="{{ $event->title }}">
                @else
                    <div class="flex flex-col items-center justify-center text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-medium tracking-wider uppercase opacity-50">No Cover Image</span>
                    </div>
                @endif
            </div>

            <div class="flex items-start justify-between p-4 border-b border-gray-600/50 bg-white/5">
                <div class="pr-2">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="text-lg font-bold !text-white line-clamp-1" title="{{ $event->title }}">{{ $event->title }}</h3>
                        @if($event->is_visible)
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold tracking-wide uppercase bg-green-900/30 text-green-400 border border-green-700/50">Visible</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold tracking-wide uppercase bg-gray-800 text-gray-400 border border-gray-600">Hidden</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5 text-sm text-gray-300">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $event->date->format('M j, Y \a\t g:i A') }}
                    </div>
                </div>
                <div class="flex flex-shrink-0 items-center gap-1.5 mt-1">
                    <button wire:click="toggleVisible({{ $event->id }})" 
                        class="p-1.5 rounded-lg border transition-all focus:outline-none focus:ring-2 {{ $event->is_visible ? 'text-green-400 bg-green-900/30 border-green-700/50 hover:bg-green-900/50 focus:ring-green-500/50' : 'text-gray-400 bg-white/5 border-white/10 hover:text-green-400 hover:bg-white/10 focus:ring-amber-500/50' }}" 
                        title="{{ $event->is_visible ? 'Hide Event' : 'Show Event' }}">
                        @if($event->is_visible)
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        @else
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        @endif
                    </button>
                    
                    <button wire:click="openEdit({{ $event->id }})" class="p-1.5 text-gray-400 bg-white/5 rounded-lg hover:text-amber-400 hover:bg-white/10 border border-white/10 transition-all focus:outline-none focus:ring-2 focus:ring-amber-500/50" title="Edit Event">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                    
                    <button wire:click="confirmDelete({{ $event->id }})" class="p-1.5 text-gray-400 bg-white/5 rounded-lg hover:text-red-400 hover:bg-red-500/20 border border-white/10 transition-all focus:outline-none focus:ring-2 focus:ring-red-500/50" title="Delete Event">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="flex-1 p-4 bg-transparent">
                @if($event->description)
                    <p class="text-sm text-gray-400 whitespace-pre-line">{{ $event->description }}</p>
                @else
                    <p class="text-sm italic text-gray-600">No description provided.</p>
                @endif
            </div>
        </div>
        @empty
        <div class="p-8 text-center border shadow-inner col-span-full bg-white/5 backdrop-blur-md border-white/10 rounded-xl">
            <p class="text-sm text-gray-400">No events yet</p>
            <p class="text-xs text-gray-400 mt-0.5">Create your first event to share with members</p>
        </div>
        @endforelse
    </div>

    @if($showDeleteModal && $selectedEvent)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-md">
            <div class="w-full max-w-sm p-6 mx-4 shadow-2xl bg-[#1a1a1a] border border-red-500/50 rounded-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-600 to-red-400"></div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 rounded-full bg-red-900/30 text-red-400 border border-red-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-white">Delete Event</h2>
                </div>
                <p class="mb-8 text-sm text-[#a0a0a0]">Are you sure you want to permanently delete <strong class="text-white">{{ $selectedEvent->title }}</strong>? This action cannot be undone.</p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)" class="px-5 py-2.5 text-sm font-semibold text-[#a0a0a0] transition-colors border border-[#404040] rounded-lg hover:bg-[#252525] hover:text-white">Cancel</button>
                    <button wire:click="executeDelete" class="px-5 py-2.5 text-sm font-bold text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700 shadow-lg shadow-red-600/20">Delete Event</button>
                </div>
            </div>
        </div>
    @endif
</div>