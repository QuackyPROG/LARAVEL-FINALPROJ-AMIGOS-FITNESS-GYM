@php
    $steps = [1 => 'Details', 2 => 'Plan', 3 => 'Agreements', 4 => 'ID', 5 => 'Payment'];
@endphp

<div class="rg-wizard">
    <div class="rg-progress" aria-label="Registration progress">
        @foreach($steps as $step => $label)
            <div class="rg-progress-item {{ $currentStep > $step ? 'is-complete' : ($currentStep === $step ? 'is-active' : '') }}">
                <div class="rg-progress-marker">
                    @if($currentStep > $step)
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        {{ $step }}
                    @endif
                </div>
                <span class="rg-progress-label">{{ $label }}</span>
            </div>
        @endforeach
    </div>

    <div class="rg-card">
        @if($currentStep === 1)
            <div>
                <span class="rg-step-eyebrow">Step 1 of 5</span>
                <h2 class="rg-heading">Personal Details</h2>

                <div class="rg-field-grid">
                    <div class="rg-field rg-field--full">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" wire:model="name" placeholder="e.g. Juan dela Cruz" class="rg-input">
                        <p class="rg-field-help">Use the same name shown on your valid ID.</p>
                        @error('name') <p class="rg-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="rg-field">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" wire:model="email" placeholder="you@email.com" class="rg-input">
                        @error('email') <p class="rg-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="rg-field">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" wire:model="phone" placeholder="+63 9XX XXX XXXX" class="rg-input">
                        @error('phone') <p class="rg-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="rg-field rg-field--full">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" wire:model="dob" class="rg-input">
                        @error('dob') <p class="rg-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

        @elseif($currentStep === 2)
            <div>
                <span class="rg-step-eyebrow">Step 2 of 5</span>
                <h2 class="rg-heading">Choose Your Plan</h2>

                <div class="rg-plan-grid">
                    @foreach($plans as $plan)
                        <label wire:click="$set('planId', {{ $plan->id }})" class="rg-plan {{ $planId == $plan->id ? 'is-selected' : '' }}">
                            <input type="radio" wire:model="planId" name="planId" value="{{ $plan->id }}" class="sr-only">
                            <div class="rg-plan-top">
                                <div>
                                    <h3 class="rg-plan-name">{{ $plan->name }}</h3>
                                    <p class="rg-plan-term">{{ $plan->duration_days }}-day access</p>
                                </div>
                                <span class="rg-price">₱{{ number_format($plan->price, 0) }}</span>
                            </div>

                            <ul class="rg-benefits">
                                @foreach(($plan->benefits ?? []) as $benefit)
                                    <li>{{ $benefit }}</li>
                                @endforeach
                            </ul>
                        </label>
                    @endforeach
                </div>

                @error('planId') <p class="rg-error" style="margin-top: 10px;">{{ $message }}</p> @enderror
            </div>

        @elseif($currentStep === 3)
            <div>
                <span class="rg-step-eyebrow">Step 3 of 5</span>
                <h2 class="rg-heading">Legal Agreements</h2>
                <p class="rg-muted">Please read each document carefully and confirm your agreement. All four are required to proceed.</p>

                <div class="rg-docs">
                    @foreach($legalDocs as $key => $doc)
                        @php $consentProp = $doc['consent']; @endphp
                        <div class="rg-doc">
                            <h3 class="rg-doc-title">{{ $doc['title'] }}</h3>
                            <div class="rg-doc-body">
                                {!! $doc['body'] !!}
                            </div>
                            <label class="rg-check">
                                <input type="checkbox" wire:model.live="{{ $consentProp }}">
                                <span>{{ $doc['label'] }}</span>
                            </label>
                            @error($consentProp) <p class="rg-error" style="padding: 0 15px 12px;">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </div>

        @elseif($currentStep === 4)
            <div>
                <span class="rg-step-eyebrow">Step 4 of 5</span>
                <h2 class="rg-heading">Identity Verification</h2>
                <p class="rg-muted">Upload a valid government-issued ID for membership verification. JPG, PNG, and PDF files are accepted up to 5 MB.</p>

                <div x-data="{ fileName: '', fileSize: '' }" class="rg-upload" style="margin-top: 16px;">
                    <div class="rg-upload-head">
                        <div class="rg-upload-icon" aria-hidden="true">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0l-4 4m4-4l4 4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="rg-upload-title">Upload your government ID</p>
                            <label for="governmentId" class="rg-file-label">Choose JPG, PNG, or PDF</label>
                        </div>
                    </div>

                    <input type="file" id="governmentId" wire:model="governmentId" accept="image/*,.pdf"
                        class="rg-input"
                        x-on:change="
                            const f = $event.target.files[0];
                            fileName = f ? f.name : '';
                            fileSize = f ? (f.size < 1048576 ? (f.size / 1024).toFixed(0) + ' KB' : (f.size / 1048576).toFixed(1) + ' MB') : '';
                        ">

                    <p class="rg-upload-meta">Accepted: JPG, PNG, PDF. Max 5 MB.</p>

                    <div wire:loading wire:target="governmentId" class="rg-upload-meta">
                        Uploading...
                    </div>

                    <div wire:loading.remove wire:target="governmentId" x-show="fileName" x-cloak class="rg-file-chip">
                        <span x-text="fileName"></span>
                        <span style="color: #b6a06a;" x-text="fileSize"></span>
                    </div>

                    @error('governmentId') <p class="rg-error" style="margin-top: 8px;">{{ $message }}</p> @enderror
                </div>

                <div class="rg-secure">
                    Your ID is stored in a private storage location. It is only accessible to gym administrators for identity verification and is never shared with third parties.
                </div>
            </div>

        @elseif($currentStep === 5)
            <div>
                <span class="rg-step-eyebrow">Step 5 of 5</span>
                <h2 class="rg-heading">Review &amp; Pay</h2>

                <div class="rg-review">
                    <div class="rg-review-row">
                        <p class="rg-review-label">Name</p>
                        <p class="rg-review-value">{{ $name }}</p>
                    </div>
                    <div class="rg-review-row">
                        <p class="rg-review-label">Email</p>
                        <p class="rg-review-value">{{ $email }}</p>
                    </div>

                    @if($selectedPlan)
                        <div class="rg-review-row">
                            <p class="rg-review-label">Plan</p>
                            <p class="rg-review-value">{{ $selectedPlan->name }}</p>
                        </div>
                        <div class="rg-review-row is-total">
                            <p class="rg-review-label">Amount</p>
                            <p class="rg-review-value">₱{{ number_format($selectedPlan->price, 2) }}</p>
                        </div>
                        <div class="rg-review-row">
                            <p class="rg-review-label">Duration</p>
                            <p class="rg-review-value">{{ $selectedPlan->duration_days }} days</p>
                        </div>
                        <div class="rg-review-row">
                            <p class="rg-review-label">Estimated Expiry</p>
                            <p class="rg-review-value">{{ now()->addDays($selectedPlan->duration_days)->format('M j, Y') }}</p>
                        </div>
                    @endif
                </div>

                <div class="rg-success">
                    All legal agreements accepted: Terms &amp; Conditions, Membership Contract, Liability Waiver, and Privacy Policy.
                </div>

                <p class="rg-muted" style="margin-top: 14px;">
                    You will be redirected to PayMongo's secure checkout to complete payment via GCash, Maya, credit/debit card, or GrabPay.
                </p>

                <button
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-wait"
                    class="rg-btn rg-btn-primary rg-btn-wide">
                    <span wire:loading.remove wire:target="submit">Pay Now — ₱{{ $selectedPlan ? number_format($selectedPlan->price, 2) : '0.00' }}</span>
                    <span wire:loading wire:target="submit">Redirecting to PayMongo...</span>
                </button>

                @error('email') <p class="rg-error" style="margin-top: 10px;">{{ $message }}</p> @enderror
            </div>
        @endif

        <div class="rg-actions">
            <div>
                @if($currentStep > 1)
                    <button wire:click="prevStep" class="rg-btn rg-btn-secondary">Back</button>
                @endif
            </div>

            <div>
                @if($currentStep < 5)
                    <button wire:click="nextStep"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-75"
                        class="rg-btn rg-btn-primary">
                        <span wire:loading.remove wire:target="nextStep">Continue</span>
                        <span wire:loading wire:target="nextStep">Validating...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>