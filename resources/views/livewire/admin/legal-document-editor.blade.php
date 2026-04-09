<div>
    <div>
        <div>
            <p>Legal Document Editor</p>
            <h2>{{ $title }}</h2>
            <p>Current version: <strong>v{{ $version }}</strong> — saving will increment to v{{ $version + 1 }}</p>
        </div>
        <div>
            <button
                wire:click="draftWithAi"
                wire:loading.attr="disabled"
                wire:target="draftWithAi">
                <span wire:loading.remove wire:target="draftWithAi">Draft with AI</span>
                <span wire:loading wire:target="draftWithAi">Drafting...</span>
            </button>
            <button wire:click="preview">Preview</button>
        </div>
    </div>

    @if($draftError)
        <div>{{ $draftError }}</div>
    @endif

    @if($saved)
        <div>
            Document saved and version incremented to v{{ $version }}.
        </div>
    @endif

    {{-- HTML Editor --}}
    <div>
        <div>
            <span>HTML Editor</span>
            <span>Supports HTML tags: &lt;h2&gt; &lt;h3&gt; &lt;p&gt; &lt;ul&gt; &lt;li&gt; &lt;strong&gt; &lt;em&gt; &lt;table&gt;</span>
        </div>
        <textarea
            wire:model="body"
            wire:dirty.class="ring-1 ring-amber-400"
            rows="24"
            placeholder="Enter document HTML here..."
        ></textarea>
    </div>

    @if(str_contains($slug, 'membership-contract'))
        <div>
            <p>
                <strong>Merge tags available for this document:</strong>
                <code>&#123;&#123;member_name&#125;&#125;</code>
                <code>&#123;&#123;plan_name&#125;&#125;</code>
                <code>&#123;&#123;plan_price&#125;&#125;</code>
                <code>&#123;&#123;start_date&#125;&#125;</code>
                <code>&#123;&#123;gym_name&#125;&#125;</code>
                — these are replaced with real member data during registration.
            </p>
        </div>
    @endif

    <div>
        <a href="{{ route('admin.legal.index') }}">Cancel</a>
        <button
            wire:click="save"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-75 cursor-wait">
            <span wire:loading.remove wire:target="save">Save & Increment Version</span>
            <span wire:loading wire:target="save">Saving...</span>
        </button>
    </div>

    {{-- Preview Modal --}}
    <flux:modal wire:model="showPreview">
        <div>
            <div>
                <h3>Preview — {{ $title }}</h3>
                <span>Merge tags shown with example values</span>
            </div>
            <div>
                {!! $previewBody !!}
            </div>
            <div>
                <button wire:click="$set('showPreview', false)">Close</button>
            </div>
        </div>
    </flux:modal>

    {{-- AI Draft Preview Modal --}}
    <flux:modal wire:model="showDraftPreview">
        <div>
            <div>
                <h3>AI Draft — {{ $title }}</h3>
                <span>Review before using</span>
            </div>
            <p>This is an AI-generated draft. Review carefully and consult a legal professional before publishing. Click "Use This Draft" to load it into the editor — it will not be saved until you click "Save & Increment Version".</p>
            <div>
                {!! $draftBody !!}
            </div>
            <div>
                <button wire:click="$set('showDraftPreview', false)">Discard</button>
                <button wire:click="useDraft">Use This Draft</button>
            </div>
        </div>
    </flux:modal>
</div>
