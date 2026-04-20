<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">{{ $title }}</h1>
        <p class="text-gray-300">Current version: <strong>v{{ $version }}</strong> — saving will increment to v{{ $version + 1 }}</p>
    </div>

    @if($draftError)
        <div class="bg-red-900/20 border border-red-700 text-red-300 text-sm px-4 py-3 rounded-md mb-4">{{ $draftError }}</div>
    @endif

    @if($saved)
        <div class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4">
            Document saved and version incremented to v{{ $version }}.
        </div>
    @endif

    <div class="flex gap-2 mb-4">
        <button
            wire:click="draftWithAi"
            wire:loading.attr="disabled"
            wire:target="draftWithAi"
            class="border border-gray-600 text-gray-300 text-sm px-3 py-1.5 rounded-md hover:bg-gray-700 transition-colors">
            <span wire:loading.remove wire:target="draftWithAi">Draft with AI</span>
            <span wire:loading wire:target="draftWithAi">Drafting...</span>
        </button>
        <button wire:click="preview" class="border border-gray-600 text-gray-300 text-sm px-3 py-1.5 rounded-md hover:bg-gray-700 transition-colors">Preview</button>
    </div>

    {{-- HTML Editor --}}
    <div class="bg-dark-card border border-gray-600 rounded-md overflow-hidden mb-4">
        <div class="px-4 py-2 border-b border-gray-600 bg-dark-page flex items-center justify-between">
            <span class="text-xs font-medium text-gray-300">HTML Editor</span>
            <span class="text-xs text-gray-400">Supports HTML tags: &lt;h2&gt; &lt;h3&gt; &lt;p&gt; &lt;ul&gt; &lt;li&gt; &lt;strong&gt; &lt;em&gt; &lt;table&gt;</span>
        </div>
        <textarea
            wire:model="body"
            wire:dirty.class="ring-1 ring-amber-400"
            rows="24"
            placeholder="Enter document HTML here..."
            class="w-full px-4 py-3 text-sm font-mono border-0 focus:ring-0 resize-none bg-dark-page text-white placeholder-gray-400"
        ></textarea>
    </div>

    @if(str_contains($slug, 'membership-contract'))
        <div class="bg-yellow-900/20 border border-yellow-700 rounded-md p-4 text-sm text-yellow-300 mb-4">
            <p>
                <strong>Merge tags available for this document:</strong>
                <code class="bg-dark-page border border-gray-600 rounded px-1 py-0.5 text-xs font-mono text-gray-400">&#123;&#123;member_name&#125;&#125;</code>
                <code class="bg-dark-page border border-gray-600 rounded px-1 py-0.5 text-xs font-mono text-gray-400">&#123;&#123;plan_name&#125;&#125;</code>
                <code class="bg-dark-page border border-gray-600 rounded px-1 py-0.5 text-xs font-mono text-gray-400">&#123;&#123;plan_price&#125;&#125;</code>
                <code class="bg-dark-page border border-gray-600 rounded px-1 py-0.5 text-xs font-mono text-gray-400">&#123;&#123;start_date&#125;&#125;</code>
                <code class="bg-dark-page border border-gray-600 rounded px-1 py-0.5 text-xs font-mono text-gray-400">&#123;&#123;gym_name&#125;&#125;</code>
                — these are replaced with real member data during registration.
            </p>
        </div>
    @endif

    <div class="flex items-center justify-between pt-6">
        <a href="{{ route('admin.legal.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors underline">Cancel</a>
        <button
            wire:click="save"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-75 cursor-wait"
            class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md">
            <span wire:loading.remove wire:target="save">Save & Increment Version</span>
            <span wire:loading wire:target="save">Saving...</span>
        </button>
    </div>

    {{-- Preview Modal --}}
    <flux:modal wire:model="showPreview">
        <div class="p-2">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-base font-semibold text-white">Preview — {{ $title }}</h3>
                    <span class="text-xs text-gray-400">Merge tags shown with example values</span>
                </div>
            </div>
            <div class="text-sm text-gray-300 max-h-96 overflow-y-auto border border-gray-600 bg-dark-page rounded p-3">
                {!! $previewBody !!}
            </div>
            <div class="mt-4 flex justify-end">
                <button wire:click="$set('showPreview', false)" class="border border-gray-600 text-gray-300 hover:bg-gray-700 transition-colors text-sm px-4 py-2 rounded-md">Close</button>
            </div>
        </div>
    </flux:modal>

    {{-- AI Draft Preview Modal --}}
    <flux:modal wire:model="showDraftPreview">
        <div class="p-2">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <h3 class="text-base font-semibold text-white">AI Draft — {{ $title }}</h3>
                    <span class="text-xs text-gray-400">Review before using</span>
                </div>
            </div>
            <p class="text-sm text-gray-400 mb-4">This is an AI-generated draft. Review carefully and consult a legal professional before publishing. Click "Use This Draft" to load it into the editor — it will not be saved until you click "Save & Increment Version".</p>
            <div class="text-sm text-gray-300 max-h-64 overflow-y-auto border border-gray-600 bg-dark-page rounded p-3 mb-4">
                {!! $draftBody !!}
            </div>
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showDraftPreview', false)" class="border border-gray-600 text-gray-300 hover:bg-gray-700 transition-colors text-sm px-4 py-2 rounded-md">Discard</button>
                <button wire:click="useDraft" class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md">Use This Draft</button>
            </div>
        </div>
    </flux:modal>
</div>
