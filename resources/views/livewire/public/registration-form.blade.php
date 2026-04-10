{{-- Step progress track --}}
@php
    $steps = [1 => 'Details', 2 => 'Plan', 3 => 'Agreements', 4 => 'ID', 5 => 'Payment'];
@endphp

<div class="max-w-2xl mx-auto">
    {{-- Progress Track --}}
    <div class="flex items-center mb-8" aria-label="Registration progress">
        @foreach($steps as $step => $label)
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm {{ $currentStep > $step ? 'bg-gray-900 border-gray-900 text-white' : ($currentStep === $step ? 'border-gray-900 text-gray-900 font-semibold' : 'border-gray-200 text-gray-300') }}">
                    @if($currentStep > $step)
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 3.293 9.879a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        {{ $step }}
                    @endif
                </div>
                <span class="text-xs text-gray-500 mt-1">{{ $label }}</span>
            </div>
            @if($step < 5)
                <div class="flex-1 h-px bg-gray-200 mx-2 mb-4"></div>
            @endif
        @endforeach
    </div>

    {{-- Step Card --}}
    <div class="bg-white border border-gray-200 rounded-md p-6">

        {{-- ───── STEP 1: Personal Details ───── --}}
        @if($currentStep === 1)
            <div>
                <p class="text-xs text-gray-400 mb-1">Step 1 of 5</p>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Details</h2>

                <div class="space-y-4">
                    <div class="flex flex-col gap-1">
                        <label for="name" class="text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="name" wire:model="name" placeholder="e.g. Juan dela Cruz" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                        @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="email" class="text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" wire:model="email" placeholder="you@email.com" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                        @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="phone" class="text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" id="phone" wire:model="phone" placeholder="+63 9XX XXX XXXX" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                        @error('phone') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="dob" class="text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" id="dob" wire:model="dob" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                        @error('dob') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

        {{-- ───── STEP 2: Plan Selection ───── --}}
        @elseif($currentStep === 2)
            <div>
                <p class="text-xs text-gray-400 mb-1">Step 2 of 5</p>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Choose Your Plan</h2>

                <div class="space-y-3">
                    @foreach($plans as $plan)
                        <label wire:click="$set('planId', {{ $plan->id }})" class="block border border-gray-200 rounded-md p-4 cursor-pointer {{ $planId == $plan->id ? 'border-gray-900 bg-gray-50' : '' }}">
                            <input type="radio" wire:model="planId" name="planId" value="{{ $plan->id }}" class="sr-only">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $plan->name }}</h3>
                                    <p class="text-sm text-gray-400">{{ $plan->duration_days }}-day access</p>
                                </div>
                                <span class="text-lg font-semibold text-gray-900">₱{{ number_format($plan->price, 0) }}</span>
                            </div>
                            <ul class="mt-3 text-sm text-gray-500 space-y-1 list-disc pl-4">
                                @foreach(($plan->benefits ?? []) as $benefit)
                                    <li>{{ $benefit }}</li>
                                @endforeach
                            </ul>
                        </label>
                    @endforeach
                </div>
                @error('planId') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
            </div>

        {{-- ───── STEP 3: Legal Agreements ───── --}}
        @elseif($currentStep === 3)
            <div>
                <p class="text-xs text-gray-400 mb-1">Step 3 of 5</p>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Legal Agreements</h2>
                <p class="text-sm text-gray-500 mb-4">Please read each document carefully and check the box to confirm your agreement. All four are required to proceed.</p>

                <div class="space-y-4">
                    @foreach($legalDocs as $key => $doc)
                        @php $consentProp = $doc['consent']; @endphp
                        <div class="border border-gray-200 rounded-md overflow-hidden">
                            <h3 class="px-4 py-3 border-b border-gray-100 font-medium text-sm text-gray-900 bg-gray-50">{{ $doc['title'] }}</h3>
                            <div class="max-h-40 overflow-y-auto px-4 py-3 text-sm text-gray-600">
                                {!! $doc['body'] !!}
                            </div>
                            <label class="flex items-center gap-2 px-4 py-3 border-t border-gray-100 bg-white text-sm text-gray-700">
                                <input type="checkbox" wire:model.live="{{ $consentProp }}">
                                <span>{{ $doc['label'] }}</span>
                            </label>
                            @error($consentProp) <p class="px-4 pb-2 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </div>

        {{-- ───── STEP 4: Identity Verification ───── --}}
        @elseif($currentStep === 4)
            <div>
                <p class="text-xs text-gray-400 mb-1">Step 4 of 5</p>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Identity Verification</h2>
                <p class="text-sm text-gray-500 mb-4">Upload a valid government-issued ID. This is required for membership verification and is stored securely.</p>

                <div x-data="{ fileName: '', fileSize: '' }" class="space-y-2">
                    <label for="governmentId" class="text-sm font-medium text-gray-700">Government ID</label>
                    <div>
                        <input type="file" id="governmentId" wire:model="governmentId" accept="image/*,.pdf"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full"
                            x-on:change="
                                const f = $event.target.files[0];
                                fileName = f ? f.name : '';
                                fileSize = f ? (f.size < 1048576 ? (f.size / 1024).toFixed(0) + ' KB' : (f.size / 1048576).toFixed(1) + ' MB') : '';
                            ">
                    </div>
                    <p class="text-xs text-gray-400">Accepted: JPG, PNG, PDF. Max 5 MB.</p>
                    <div wire:loading wire:target="governmentId" class="text-sm text-gray-400">
                        Uploading...
                    </div>
                    <div wire:loading.remove wire:target="governmentId" x-show="fileName" x-cloak class="text-sm text-gray-600 flex items-center gap-2">
                        <span x-text="fileName"></span>
                        <span class="text-gray-400" x-text="fileSize"></span>
                    </div>
                    @error('governmentId') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-md p-3 mt-4">
                    <p class="text-xs text-gray-500">
                        Your ID is stored in a private, encrypted storage location. It is only accessible to gym administrators for identity verification and is never shared with third parties.
                    </p>
                </div>
            </div>

        {{-- ───── STEP 5: Review & Pay ───── --}}
        @elseif($currentStep === 5)
            <div>
                <p class="text-xs text-gray-400 mb-1">Step 5 of 5</p>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Review &amp; Pay</h2>

                <div class="bg-gray-50 border border-gray-100 rounded-md p-4 mb-4">
                    <div>
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <p class="text-xs text-gray-400 uppercase">Name</p>
                            <p class="text-sm font-medium text-gray-900">{{ $name }}</p>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-100">
                            <p class="text-xs text-gray-400 uppercase">Email</p>
                            <p class="text-sm font-medium text-gray-900">{{ $email }}</p>
                        </div>
                        @if($selectedPlan)
                            <div class="flex justify-between py-1.5 border-b border-gray-100">
                                <p class="text-xs text-gray-400 uppercase">Plan</p>
                                <p class="text-sm font-medium text-gray-900">{{ $selectedPlan->name }}</p>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-gray-100">
                                <p class="text-xs text-gray-400 uppercase">Amount</p>
                                <p class="text-sm font-semibold text-gray-900">₱{{ number_format($selectedPlan->price, 2) }}</p>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-gray-100">
                                <p class="text-xs text-gray-400 uppercase">Duration</p>
                                <p class="text-sm font-medium text-gray-900">{{ $selectedPlan->duration_days }} days</p>
                            </div>
                            <div class="flex justify-between py-1.5">
                                <p class="text-xs text-gray-400 uppercase">Estimated Expiry</p>
                                <p class="text-sm font-medium text-gray-900">{{ now()->addDays($selectedPlan->duration_days)->format('M j, Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-md px-4 py-3 mb-4">
                    <p class="text-sm text-green-700">
                        ✓ All legal agreements accepted — Terms &amp; Conditions, Membership Contract, Liability Waiver, Privacy Policy
                    </p>
                </div>

                <p class="text-xs text-gray-400 mb-4">
                    You will be redirected to PayMongo's secure checkout to complete payment via GCash, Maya, credit/debit card, or GrabPay. Your membership will be activated upon confirmed payment.
                </p>

                <button
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-wait"
                    class="w-full bg-gray-900 text-white text-sm px-4 py-3 rounded-md">
                    <span wire:loading.remove wire:target="submit">Pay Now — ₱{{ $selectedPlan ? number_format($selectedPlan->price, 2) : '0.00' }}</span>
                    <span wire:loading wire:target="submit">Redirecting to PayMongo...</span>
                </button>

                @error('email') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
            </div>
        @endif

        {{-- Navigation Footer --}}
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
            <div>
                @if($currentStep > 1 && $currentStep < 5)
                    <button wire:click="prevStep" class="border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">Back</button>
                @endif

                @if($currentStep === 5)
                    <button wire:click="prevStep" class="border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">Back</button>
                @endif
            </div>

            <div>
                @if($currentStep < 5)
                    <button wire:click="nextStep"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-75"
                        class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">
                        <span wire:loading.remove wire:target="nextStep">Continue</span>
                        <span wire:loading wire:target="nextStep">Validating...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
