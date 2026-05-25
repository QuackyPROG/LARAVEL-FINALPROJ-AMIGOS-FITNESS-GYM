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

                    <div class="rg-field rg-field--full"
                        x-data="{
                            open: false,
                            displayValue: '',
                            panelTop: 0,
                            panelLeft: 0,
                            panelWidth: 0,
                            viewYear: new Date().getFullYear() - 18,
                            viewMonth: new Date().getMonth(),
                            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
                            get viewMonthName() { return this.months[this.viewMonth]; },
                            get daysInMonth() {
                                return new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
                            },
                            get firstDayOfWeek() {
                                return new Date(this.viewYear, this.viewMonth, 1).getDay();
                            },
                            get calendarCells() {
                                const cells = [];
                                for (let i = 0; i < this.firstDayOfWeek; i++) cells.push(null);
                                for (let d = 1; d <= this.daysInMonth; d++) cells.push(d);
                                return cells;
                            },
                            isToday(d) {
                                const t = new Date();
                                return d === t.getDate() && this.viewMonth === t.getMonth() && this.viewYear === t.getFullYear();
                            },
                            isSelected(d) {
                                if (!this.displayValue || !d) return false;
                                const [y, m, day] = this.displayValue.split('-').map(Number);
                                return d === day && this.viewMonth === m - 1 && this.viewYear === y;
                            },
                            isFuture(d) {
                                const sel = new Date(this.viewYear, this.viewMonth, d);
                                return sel > new Date();
                            },
                            toggleOpen() {
                                if (!this.open) {
                                    const rect = this.$refs.trigger.getBoundingClientRect();
                                    this.panelTop   = rect.bottom + window.scrollY + 6;
                                    this.panelLeft  = rect.left  + window.scrollX;
                                    this.panelWidth = rect.width;
                                }
                                this.open = !this.open;
                            },
                            selectDay(d) {
                                if (!d || this.isFuture(d)) return;
                                const mm = String(this.viewMonth + 1).padStart(2,'0');
                                const dd = String(d).padStart(2,'0');
                                const val = this.viewYear + '-' + mm + '-' + dd;
                                this.displayValue = val;
                                $wire.set('dob', val);
                                this.open = false;
                            },
                            prevMonth() {
                                if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; }
                                else this.viewMonth--;
                            },
                            nextMonth() {
                                if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; }
                                else this.viewMonth++;
                            },
                            prevYear()  { this.viewYear--; },
                            nextYear()  { this.viewYear++; },
                            formatDisplay(v) {
                                if (!v) return '';
                                const [y, m, d] = v.split('-').map(Number);
                                return this.months[m-1] + ' ' + d + ', ' + y;
                            },
                            init() {
                                const existing = @js($this->dob ?? '');
                                if (existing) {
                                    this.displayValue = existing;
                                    const [y, m] = existing.split('-').map(Number);
                                    this.viewYear  = y;
                                    this.viewMonth = m - 1;
                                }
                                // Close on outside click
                                document.addEventListener('click', (e) => {
                                    if (!this.$el.contains(e.target) && !document.getElementById('rg-dp-panel')?.contains(e.target)) {
                                        this.open = false;
                                    }
                                });
                            }
                        }">

                        <label>Date of Birth</label>

                        {{-- Trigger button --}}
                        <button type="button"
                            x-ref="trigger"
                            @click="toggleOpen()"
                            class="rg-input rg-datepicker-trigger"
                            :class="open ? 'is-open' : ''"
                            aria-haspopup="true"
                            :aria-expanded="open">
                            <span x-text="displayValue ? formatDisplay(displayValue) : ''" class="rg-datepicker-value"></span>
                            <span x-show="!displayValue" class="rg-datepicker-placeholder">Select your date of birth</span>
                            <svg class="rg-datepicker-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8"  y1="2" x2="8"  y2="6"/>
                                <line x1="3"  y1="10" x2="21" y2="10"/>
                            </svg>
                        </button>

                        {{-- Dropdown calendar — rendered via Teleport to <body> to escape backdrop-filter stacking context --}}
                        <template x-teleport="body">
                            <div id="rg-dp-panel"
                                x-show="open"
                                x-transition:enter="dp-enter"
                                x-transition:enter-start="dp-enter-start"
                                x-transition:enter-end="dp-enter-end"
                                x-transition:leave="dp-leave"
                                x-transition:leave-start="dp-leave-start"
                                x-transition:leave-end="dp-leave-end"
                                x-cloak
                                :style="`position: absolute; top: ${panelTop}px; left: ${panelLeft}px; width: ${panelWidth}px; min-width: 300px;`"
                                class="rg-datepicker-panel"
                                role="dialog" aria-label="Date picker">

                                {{-- Header: year --}}
                                <div class="rg-dp-row rg-dp-year-row">
                                    <button type="button" @click="prevYear()" class="rg-dp-nav" aria-label="Previous year">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/></svg>
                                    </button>
                                    <span class="rg-dp-year" x-text="viewYear"></span>
                                    <button type="button" @click="nextYear()" class="rg-dp-nav" aria-label="Next year">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg>
                                    </button>
                                </div>

                                {{-- Header: month --}}
                                <div class="rg-dp-row rg-dp-month-row">
                                    <button type="button" @click="prevMonth()" class="rg-dp-nav" aria-label="Previous month">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                                    </button>
                                    <span class="rg-dp-month" x-text="viewMonthName"></span>
                                    <button type="button" @click="nextMonth()" class="rg-dp-nav" aria-label="Next month">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                    </button>
                                </div>

                                {{-- Weekday labels --}}
                                <div class="rg-dp-weekdays">
                                    @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $wd)
                                        <span>{{ $wd }}</span>
                                    @endforeach
                                </div>

                                {{-- Day grid --}}
                                <div class="rg-dp-grid">
                                    <template x-for="(cell, idx) in calendarCells" :key="idx">
                                        <button type="button"
                                            @click="selectDay(cell)"
                                            :disabled="!cell || isFuture(cell)"
                                            :class="{
                                                'rg-dp-day': true,
                                                'is-empty':    !cell,
                                                'is-selected': isSelected(cell),
                                                'is-today':    isToday(cell) && !isSelected(cell),
                                                'is-future':   cell && isFuture(cell)
                                            }"
                                            x-text="cell || ''">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

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
                <p class="rg-muted">Select your ID type, enter the ID number, and upload a photo or scan of the ID for membership verification.</p>

                {{-- ID Type & Number --}}
                <div class="rg-field-grid" style="margin-top: 16px;">
                    <div class="rg-field rg-field--full">
                        <label for="idType">ID Type</label>
                        <select id="idType" wire:model.live="idType" class="rg-input">
                            <option value="">Select your ID type…</option>
                            <option value="national">PhilSys National ID (1234-5678-9012)</option>
                            <option value="passport">Passport (P1234567A)</option>
                            <option value="drivers_license">Driver's License (A01-23-456789)</option>
                            <option value="sss">SSS ID (12-3456789-0)</option>
                            <option value="philhealth">PhilHealth ID (12 digits)</option>
                            <option value="pagibig">Pag-IBIG ID (12 digits)</option>
                        </select>
                        @error('idType') <p class="rg-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="rg-field rg-field--full">
                        <label for="idNumber">ID Number</label>
                        <input type="text" id="idNumber" wire:model="idNumber" placeholder="Enter your ID number" class="rg-input">
                        @error('idNumber') <p class="rg-error">{{ $message }}</p> @enderror
                    </div>
                </div>

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