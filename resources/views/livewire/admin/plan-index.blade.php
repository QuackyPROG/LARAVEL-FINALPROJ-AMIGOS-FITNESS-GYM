<div>
    <div>
        <div>
            <h1>Membership Plans</h1>
            <p>Manage plans shown on the public site</p>
        </div>
        <button wire:click="openCreate">+ New Plan</button>
    </div>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if($showForm)
        <div>
            <h2>{{ $editingId ? 'Edit Plan' : 'New Plan' }}</h2>
            <div>
                <div>
                    <label>Plan Name</label>
                    <input type="text" wire:model="name">
                    @error('name')<p>{{ $message }}</p>@enderror
                </div>
                <div>
                    <label>Duration (days)</label>
                    <input type="number" wire:model="durationDays">
                    @error('durationDays')<p>{{ $message }}</p>@enderror
                </div>
                <div>
                    <label>Price (₱)</label>
                    <input type="number" wire:model="price" step="0.01">
                    @error('price')<p>{{ $message }}</p>@enderror
                </div>
                <div>
                    <label>Active</label>
                    <div>
                        <input type="checkbox" wire:model="isActive" id="isActive">
                        <label for="isActive">Show on public site</label>
                    </div>
                </div>
                <div>
                    <label>Benefits (one per line)</label>
                    <textarea wire:model="benefitsRaw" rows="4"></textarea>
                    @error('benefitsRaw')<p>{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <button wire:click="save">{{ $editingId ? 'Update' : 'Create' }}</button>
                <button wire:click="cancel">Cancel</button>
            </div>
        </div>
    @endif

    <div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td>{{ $plan->name }}</td>
                        <td>{{ $plan->duration_days }} days</td>
                        <td>₱{{ number_format($plan->price, 2) }}</td>
                        <td>
                            @if($plan->is_active)
                                <span>Active</span>
                            @else
                                <span>Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <button wire:click="openEdit({{ $plan->id }})">Edit</button>
                                <button wire:click="toggleActive({{ $plan->id }})">
                                    {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <p>No plans yet</p>
                            <p>Create your first membership plan to get started</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
