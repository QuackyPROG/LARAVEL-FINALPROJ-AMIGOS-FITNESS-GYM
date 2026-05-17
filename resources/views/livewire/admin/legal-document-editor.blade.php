<div>
    <x-admin-splash target="save" :successMessage="session('success')" resetFlag="saved" />
    
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Legal Documents</h1>
            <p class="text-gray-300">Edit the membership agreements, T&C, and privacy disclosures presented to members during registration.</p>
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

    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl overflow-hidden mb-4 shadow-xl">
        <table class="w-full text-sm">
            <thead class="border-b border-white/10 bg-white/5">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-200 uppercase tracking-wider py-4 px-5">Document</th>
                    <th class="text-left text-xs font-semibold text-gray-200 uppercase tracking-wider py-4 px-5">Version</th>
                    <th class="text-left text-xs font-semibold text-gray-200 uppercase tracking-wider py-4 px-5">Last Updated</th>
                    <th class="text-center text-xs font-semibold text-gray-200 uppercase tracking-wider py-4 px-5">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10 bg-transparent">
                @foreach($documents as $doc)
                    @php
                        $daysSince = $doc['updated_at']
                            ? \Carbon\Carbon::parse($doc['updated_at'])->diffInDays(now())
                            : null;
                        $needsReview = $daysSince !== null && $daysSince > 180;
                    @endphp
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="py-4 px-5 text-white">
                            <p class="font-bold text-white">{{ $doc['title'] }}</p>
                        </td>
                        <td class="py-4 px-5">
                            @if($needsReview)
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold tracking-wide uppercase bg-yellow-900/30 text-yellow-400 border border-yellow-700/50">NEEDS REVIEW v{{ $doc['version'] }}</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold tracking-wide uppercase bg-green-900/30 text-green-400 border border-green-700/50">CURRENT v{{ $doc['version'] }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-gray-400">
                            {{ $doc['updated_at'] ? \Carbon\Carbon::parse($doc['updated_at'])->format('M j, Y') : '—' }}
                        </td>
                        <td class="py-4 px-5 text-center">
                            <button wire:click="openEdit('{{ $doc['slug'] }}')" class="p-1.5 text-gray-400 bg-white/5 rounded-lg hover:text-amber-400 hover:bg-white/10 border border-white/10 transition-all focus:outline-none focus:ring-2 focus:ring-amber-500/50" title="Edit Document">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-blue-900/20 border border-blue-700/50 rounded-xl p-4 text-sm text-blue-300">
        <p>
            <strong>Important:</strong> Every save increments the document version. Members who registered under a previous version have their signed copy preserved. Review documents regularly to ensure legal compliance.
        </p>
    </div>

    {{-- MODAL FOR EDITING --}}
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
    
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div class="relative w-full max-w-4xl mx-auto group h-[90vh] flex flex-col">
            <div class="absolute -inset-[1.5px] bg-gradient-to-r from-amber-300 via-yellow-600 to-amber-400 rounded-2xl gold-gradient-bg opacity-80 blur-[2px] transition-opacity duration-500"></div>
            
            <div class="relative bg-[#000000] rounded-2xl shadow-[0_0_40px_rgba(0,0,0,0.5)] p-8 w-full h-full flex flex-col">
                
                <div class="flex items-start justify-between mb-6 shrink-0">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 bg-gradient-to-br from-amber-400/20 to-yellow-600/20 border border-amber-500/30 text-amber-400 p-3.5 rounded-full shadow-[0_0_20px_rgba(251,191,36,0.15)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <h2 class="text-2xl font-extrabold text-white tracking-wide uppercase">Edit {{ $title }}</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Current version: <strong>v{{ $version }}</strong> — saving will increment to v{{ $version + 1 }}</p>
                        </div>
                    </div>
                    <button wire:click="preview" class="text-gray-500 hover:text-amber-400 transition-colors p-1" title="Preview Document">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>

                @if($draftError)
                    <div class="bg-red-900/20 border border-red-500/50 text-red-400 text-sm px-4 py-3 rounded-xl mb-4 shadow-inner shrink-0">{{ $draftError }}</div>
                @endif

                {{-- HTML Editor --}}
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-inner flex flex-col flex-1 min-h-0 overflow-hidden mb-4">
                    <div class="px-4 py-3 border-b border-white/10 bg-white/5 flex items-center justify-between shrink-0">
                        <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">HTML Editor</span>
                        <span class="text-xs text-gray-400">Supports HTML tags: &lt;h2&gt; &lt;h3&gt; &lt;p&gt; &lt;ul&gt; &lt;li&gt; &lt;strong&gt; &lt;em&gt; &lt;table&gt;</span>
                    </div>
                    <textarea
                        wire:model="body"
                        placeholder="Enter document HTML here..."
                        class="w-full flex-1 px-5 py-4 text-sm font-mono border-0 focus:ring-0 resize-none bg-transparent text-white placeholder-gray-500 custom-scrollbar outline-none focus:outline-none"
                    ></textarea>
                </div>

                @if(str_contains($slug, 'membership-contract'))
                    <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 text-xs text-amber-300 mb-4 shrink-0 shadow-inner">
                        <p>
                            <strong class="font-bold uppercase tracking-wider text-[10px]">Merge tags available:</strong>
                            <code class="bg-black/50 border border-amber-500/30 rounded px-1.5 py-0.5 font-mono text-amber-400 mx-0.5">&#123;&#123;member_name&#125;&#125;</code>
                            <code class="bg-black/50 border border-amber-500/30 rounded px-1.5 py-0.5 font-mono text-amber-400 mx-0.5">&#123;&#123;plan_name&#125;&#125;</code>
                            <code class="bg-black/50 border border-amber-500/30 rounded px-1.5 py-0.5 font-mono text-amber-400 mx-0.5">&#123;&#123;plan_price&#125;&#125;</code>
                            <code class="bg-black/50 border border-amber-500/30 rounded px-1.5 py-0.5 font-mono text-amber-400 mx-0.5">&#123;&#123;start_date&#125;&#125;</code>
                            <code class="bg-black/50 border border-amber-500/30 rounded px-1.5 py-0.5 font-mono text-amber-400 mx-0.5">&#123;&#123;gym_name&#125;&#125;</code>
                            — these are replaced with real member data.
                        </p>
                    </div>
                @endif
                
                <div class="flex justify-end gap-3 pt-4 border-t border-white/10 shrink-0">
                    <button type="button" wire:click="$set('showForm', false)" class="px-6 py-2.5 text-sm font-semibold text-gray-300 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all">Cancel</button>
                    <button type="button" wire:click="save" class="px-6 py-2.5 text-sm font-bold text-gray-900 transition-all transform rounded-xl bg-gradient-to-r from-amber-400 to-yellow-600 hover:from-amber-500 hover:to-yellow-700 shadow-[0_0_20px_rgba(251,191,36,0.2)] hover:shadow-[0_0_25px_rgba(251,191,36,0.4)] hover:-translate-y-0.5 flex items-center gap-2">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Save & Increment
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Preview Modal --}}
    @if($showPreview)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="relative w-full max-w-4xl mx-auto flex flex-col max-h-[90vh]">
            <div class="absolute -inset-[1.5px] bg-gradient-to-r from-blue-400 via-indigo-500 to-blue-600 rounded-2xl opacity-50 blur-[2px]"></div>
            <div class="relative bg-[#111111] rounded-2xl shadow-2xl p-8 w-full flex flex-col min-h-0">
                <div class="flex items-start justify-between mb-6 shrink-0">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 bg-blue-900/30 text-blue-400 p-3 rounded-full border border-blue-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Preview — {{ $title }}</h3>
                            <p class="text-xs text-gray-400 mt-1">Merge tags shown with example values</p>
                        </div>
                    </div>
                    <button wire:click="$set('showPreview', false)" class="text-gray-500 hover:text-white transition-colors p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="text-sm text-gray-300 overflow-y-auto border border-white/10 bg-white/5 rounded-xl p-6 custom-scrollbar flex-1 mb-6 shadow-inner">
                    {!! $previewBody !!}
                </div>
                
                <div class="flex justify-end shrink-0">
                    <button wire:click="$set('showPreview', false)" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold px-6 py-2.5 rounded-xl transition-all">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- AI Draft Preview Modal --}}
    @if($showDraftPreview)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="relative w-full max-w-4xl mx-auto flex flex-col max-h-[90vh]">
            <div class="absolute -inset-[1.5px] bg-gradient-to-r from-purple-400 via-fuchsia-500 to-purple-600 rounded-2xl opacity-50 blur-[2px]"></div>
            <div class="relative bg-[#111111] rounded-2xl shadow-2xl p-8 w-full flex flex-col min-h-0">
                <div class="flex items-start justify-between mb-4 shrink-0">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 bg-purple-900/30 text-purple-400 p-3 rounded-full border border-purple-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">AI Draft — {{ $title }}</h3>
                            <p class="text-xs text-gray-400 mt-1">Review carefully before using</p>
                        </div>
                    </div>
                    <button wire:click="$set('showDraftPreview', false)" class="text-gray-500 hover:text-white transition-colors p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="bg-purple-900/20 border border-purple-500/30 rounded-xl p-4 text-xs text-purple-300 mb-6 shrink-0 shadow-inner">
                    <p><strong>Note:</strong> This is an AI-generated draft. Review carefully and consult a legal professional before publishing. Click "Use This Draft" to load it into the editor — it will not be saved until you click "Save & Increment Version".</p>
                </div>
                
                <div class="text-sm text-gray-300 overflow-y-auto border border-white/10 bg-white/5 rounded-xl p-6 custom-scrollbar flex-1 mb-6 shadow-inner">
                    {!! $draftBody !!}
                </div>
                
                <div class="flex justify-end gap-3 shrink-0">
                    <button wire:click="$set('showDraftPreview', false)" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold px-6 py-2.5 rounded-xl transition-all">Discard</button>
                    <button wire:click="useDraft" class="bg-gradient-to-r from-purple-500 to-fuchsia-600 hover:from-purple-600 hover:to-fuchsia-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 transition-all transform hover:-translate-y-0.5">Use This Draft</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
