{{-- Step progress track --}}
@php
    $steps = [1 => 'Details', 2 => 'Plan', 3 => 'Agreements', 4 => 'ID', 5 => 'Payment'];
@endphp

<div>
    {{-- Progress Track --}}
    <div aria-label="Registration progress">
        @foreach($steps as $step => $label)
            <div>
                <div>
                    @if($currentStep > $step)
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 3.293 9.879a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        {{ $step }}
                    @endif
                </div>
                <span>{{ $label }}</span>
            </div>
            @if($step < 5)
                <div></div>
            @endif
        @endforeach
    </div>

    {{-- Step Card --}}
    <div>

        {{-- ───── STEP 1: Personal Details ───── --}}
        @if($currentStep === 1)
            <div>
                <p>Step 1 of 5</p>
                <h2>Personal Details</h2>

                <div>
                    <div>
                        <label for="name">Full Name</label>
                        <input type="text" id="name" wire:model="name" placeholder="e.g. Juan dela Cruz">
                        @error('name') <p>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email">Email Address</label>
                        <input type="email" id="email" wire:model="email" placeholder="you@email.com">
                        @error('email') <p>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" wire:model="phone" placeholder="+63 9XX XXX XXXX">
                        @error('phone') <p>{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" wire:model="dob">
                        @error('dob') <p>{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

        {{-- ───── STEP 2: Plan Selection ───── --}}
        @elseif($currentStep === 2)
            <div>
                <p>Step 2 of 5</p>
                <h2>Choose Your Plan</h2>

                <div>
                    @foreach($plans as $plan)
                        <label wire:click="$set('planId', {{ $plan->id }})">
                            <input type="radio" wire:model="planId" name="planId" value="{{ $plan->id }}">
                            <div>
                                <div>
                                    <h3>{{ $plan->name }}</h3>
                                    <p>{{ $plan->duration_days }}-day access</p>
                                </div>
                                <span>₱{{ number_format($plan->price, 0) }}</span>
                            </div>
                            <ul>
                                @foreach(($plan->benefits ?? []) as $benefit)
                                    <li>{{ $benefit }}</li>
                                @endforeach
                            </ul>
                        </label>
                    @endforeach
                </div>
                @error('planId') <p>{{ $message }}</p> @enderror
            </div>

        {{-- ───── STEP 3: Legal Agreements ───── --}}
        @elseif($currentStep === 3)
            <div>
                <p>Step 3 of 5</p>
                <h2>Legal Agreements</h2>
                <p>Please read each document carefully and check the box to confirm your agreement. All four are required to proceed.</p>

                @foreach($legalDocs as $key => $doc)
                    @php $consentProp = $doc['consent']; @endphp
                    <div>
                        <h3>{{ $doc['title'] }}</h3>
                        <div>
                            {!! $doc['body'] !!}
                        </div>
                        <label>
                            <input type="checkbox" wire:model.live="{{ $consentProp }}">
                            <span>{{ $doc['label'] }}</span>
                        </label>
                        @error($consentProp) <p>{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>

        {{-- ───── STEP 4: Identity Verification ───── --}}
        @elseif($currentStep === 4)
            <div>
                <p>Step 4 of 5</p>
                <h2>Identity Verification</h2>
                <p>Upload a valid government-issued ID. This is required for membership verification and is stored securely.</p>

                <div x-data="{ fileName: '', fileSize: '' }">
                    <label for="governmentId">Government ID</label>
                    <div>
                        <input type="file" id="governmentId" wire:model="governmentId" accept="image/*,.pdf"
                            x-on:change="
                                const f = $event.target.files[0];
                                fileName = f ? f.name : '';
                                fileSize = f ? (f.size < 1048576 ? (f.size / 1024).toFixed(0) + ' KB' : (f.size / 1048576).toFixed(1) + ' MB') : '';
                            ">
                    </div>
                    <p>Accepted: JPG, PNG, PDF. Max 5 MB.</p>
                    <div wire:loading wire:target="governmentId">
                        Uploading...
                    </div>
                    <div wire:loading.remove wire:target="governmentId" x-show="fileName" x-cloak>
                        <span x-text="fileName"></span>
                        <span x-text="fileSize"></span>
                    </div>
                    @error('governmentId') <p>{{ $message }}</p> @enderror
                </div>

                <div>
                    <p>
                        Your ID is stored in a private, encrypted storage location. It is only accessible to gym administrators for identity verification and is never shared with third parties.
                    </p>
                </div>
            </div>

        {{-- ───── STEP 5: Review & Pay ───── --}}
        @elseif($currentStep === 5)
            <div>
                <p>Step 5 of 5</p>
                <h2>Review &amp; Pay</h2>

                <div>
                    <div>
                        <div>
                            <p>Name</p>
                            <p>{{ $name }}</p>
                        </div>
                        <div>
                            <p>Email</p>
                            <p>{{ $email }}</p>
                        </div>
                        @if($selectedPlan)
                            <div>
                                <p>Plan</p>
                                <p>{{ $selectedPlan->name }}</p>
                            </div>
                            <div>
                                <p>Amount</p>
                                <p>₱{{ number_format($selectedPlan->price, 2) }}</p>
                            </div>
                            <div>
                                <p>Duration</p>
                                <p>{{ $selectedPlan->duration_days }} days</p>
                            </div>
                            <div>
                                <p>Estimated Expiry</p>
                                <p>{{ now()->addDays($selectedPlan->duration_days)->format('M j, Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <p>
                        ✓ All legal agreements accepted — Terms &amp; Conditions, Membership Contract, Liability Waiver, Privacy Policy
                    </p>
                </div>

                <p>
                    You will be redirected to PayMongo's secure checkout to complete payment via GCash, Maya, credit/debit card, or GrabPay. Your membership will be activated upon confirmed payment.
                </p>

                <button
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-wait">
                    <span wire:loading.remove wire:target="submit">Pay Now — ₱{{ $selectedPlan ? number_format($selectedPlan->price, 2) : '0.00' }}</span>
                    <span wire:loading wire:target="submit">Redirecting to PayMongo...</span>
                </button>

                @error('email') <p>{{ $message }}</p> @enderror
            </div>
        @endif

        {{-- Navigation Footer --}}
        <div>
            @if($currentStep > 1 && $currentStep < 5)
                <button wire:click="prevStep">Back</button>
            @endif

            @if($currentStep === 5)
                <button wire:click="prevStep">Back</button>
            @endif

            @if($currentStep < 5)
                <button wire:click="nextStep"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75">
                    <span wire:loading.remove wire:target="nextStep">Continue</span>
                    <span wire:loading wire:target="nextStep">Validating...</span>
                </button>
            @endif
        </div>
    </div>
</div>
