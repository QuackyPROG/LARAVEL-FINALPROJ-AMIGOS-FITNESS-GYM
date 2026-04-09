<?php

namespace App\Livewire\Public;

use App\Models\MemberConsent;
use App\Models\MemberConsentSnapshot;
use App\Models\MemberProfile;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\SiteContent;
use App\Models\User;
use App\Services\PayMongoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class RegistrationForm extends Component
{
    use WithFileUploads;

    public int $currentStep = 1;

    // Step 1 — Personal Details
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|string|max:30')]
    public string $phone = '';

    #[Rule('required|date|before:today')]
    public string $dob = '';

    // Step 2 — Plan
    #[Rule('required|integer|exists:membership_plans,id')]
    public ?int $planId = null;

    // Step 3 — Legal Consents
    #[Rule('accepted')]
    public bool $consentTerms = false;

    #[Rule('accepted')]
    public bool $consentContract = false;

    #[Rule('accepted')]
    public bool $consentWaiver = false;

    #[Rule('accepted')]
    public bool $consentPrivacy = false;

    // Step 4 — Identity
    #[Rule('required|file|mimes:jpg,jpeg,png,pdf|max:5120')]
    public $governmentId = null;

    public bool $processing = false;

    public function mount(mixed $selectedPlanId = null): void
    {
        $this->planId = $selectedPlanId ? (int) $selectedPlanId : null;
    }

    public function nextStep(): void
    {
        match ($this->currentStep) {
            1 => $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:30',
                'dob' => 'required|date|before:today',
            ]),
            2 => $this->validate([
                'planId' => 'required|integer|exists:membership_plans,id',
            ]),
            3 => $this->validate([
                'consentTerms' => 'accepted',
                'consentContract' => 'accepted',
                'consentWaiver' => 'accepted',
                'consentPrivacy' => 'accepted',
            ]),
            4 => $this->validate([
                'governmentId' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]),
            default => null,
        };

        $this->currentStep++;
    }

    public function prevStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit(PayMongoService $payMongoService): mixed
    {
        // Final guard: ensure all consents were checked
        $this->validate([
            'consentTerms' => 'accepted',
            'consentContract' => 'accepted',
            'consentWaiver' => 'accepted',
            'consentPrivacy' => 'accepted',
        ]);

        $this->processing = true;

        $plan = MembershipPlan::findOrFail($this->planId);

        $ext = $this->governmentId->getClientOriginalExtension() ?: 'bin';
        $govIdPath = $this->governmentId->storeAs('gov-ids', Str::uuid().'.'.$ext, 'local');

        $user = null;
        $membership = null;

        try {
            DB::transaction(function () use ($plan, $govIdPath, &$user, &$membership): void {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'dob' => $this->dob,
                    'role' => 'member',
                    'status' => 'pending',
                    'must_change_password' => true,
                    'password' => bcrypt(Str::random(16)),
                ]);

                MemberProfile::create([
                    'user_id' => $user->id,
                    'government_id_path' => $govIdPath,
                ]);

                $membership = Membership::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'starts_at' => now()->toDateString(),
                    'expires_at' => now()->addDays($plan->duration_days)->toDateString(),
                    'status' => 'pending_payment',
                ]);
            });

            // Record legal consents + snapshots
            $this->recordConsents($user, $plan);

            $checkoutSession = $payMongoService->createCheckoutSession(
                lineItems: [
                    [
                        'name' => $plan->name,
                        'amount' => (int) ($plan->price * 100),
                        'currency' => 'PHP',
                        'quantity' => 1,
                    ],
                ],
                successUrl: route('payment.success').'?ref='.$membership->id,
                cancelUrl: route('payment.failed').'?ref='.$membership->id,
            );

            $sessionId = $checkoutSession['id'] ?? null;
            $checkoutUrl = $checkoutSession['attributes']['checkout_url'] ?? null;

            if (! $sessionId || ! $checkoutUrl) {
                throw new \RuntimeException('Invalid checkout session response from PayMongo.');
            }

            $membership->update(['checkout_session_id' => $sessionId]);

            return redirect()->away($checkoutUrl);
        } catch (\Throwable $e) {
            if ($user && $user->exists) {
                $user->profile?->delete();
                $membership?->delete();
                $user->consents()->each(fn ($c) => $c->snapshot?->delete() || $c->delete());
                $user->delete();
            }

            $this->processing = false;
            $this->addError('email', 'Payment service unavailable. Please try again.');

            return null;
        }
    }

    private function recordConsents(User $user, MembershipPlan $plan): void
    {
        $documents = [
            'terms_and_conditions' => 'legal.terms_and_conditions',
            'membership_contract' => 'legal.membership_contract',
            'liability_waiver' => 'legal.liability_waiver',
            'privacy_policy' => 'legal.privacy_policy',
        ];

        $ip = request()->ip() ?? '0.0.0.0';

        foreach ($documents as $version_key => $contentKey) {
            $version = (int) SiteContent::get($contentKey.'_version', '1');
            $body = $this->renderDocument($contentKey, $plan);

            $consent = MemberConsent::create([
                'user_id' => $user->id,
                'document_key' => $contentKey,
                'version' => $version,
                'ip_address' => $ip,
                'method' => 'online',
                'accepted_at' => now(),
            ]);

            MemberConsentSnapshot::create([
                'consent_id' => $consent->id,
                'body' => $body,
            ]);
        }
    }

    private function renderDocument(string $key, MembershipPlan $plan): string
    {
        $body = SiteContent::get($key);

        return str_replace(
            ['{{member_name}}', '{{plan_name}}', '{{plan_price}}', '{{start_date}}', '{{gym_name}}'],
            [
                $this->name,
                $plan->name,
                '₱'.number_format((float) $plan->price, 2),
                now()->format('F j, Y'),
                SiteContent::get('gym_name', 'AmigosFitnessGym'),
            ],
            $body
        );
    }

    public function render(): View
    {
        $plans = MembershipPlan::active()->orderBy('price')->get();

        // Load documents for step 3
        $selectedPlan = $this->planId ? MembershipPlan::find($this->planId) : null;
        $legalDocs = [];

        if ($this->currentStep === 3) {
            $legalDocs = [
                'terms_and_conditions' => [
                    'title' => 'Terms & Conditions',
                    'body' => SiteContent::get('legal.terms_and_conditions'),
                    'consent' => 'consentTerms',
                    'label' => 'I have read and agree to the Terms & Conditions',
                ],
                'membership_contract' => [
                    'title' => 'Membership Contract',
                    'body' => $selectedPlan
                        ? $this->renderDocument('legal.membership_contract', $selectedPlan)
                        : SiteContent::get('legal.membership_contract'),
                    'consent' => 'consentContract',
                    'label' => 'I have read and agree to the Membership Contract',
                ],
                'liability_waiver' => [
                    'title' => 'Liability Waiver',
                    'body' => SiteContent::get('legal.liability_waiver'),
                    'consent' => 'consentWaiver',
                    'label' => 'I have read and agree to the Liability Waiver',
                ],
                'privacy_policy' => [
                    'title' => 'Privacy Policy',
                    'body' => SiteContent::get('legal.privacy_policy'),
                    'consent' => 'consentPrivacy',
                    'label' => 'I consent to the collection and processing of my personal data as described',
                ],
            ];
        }

        return view('livewire.public.registration-form', compact('plans', 'legalDocs', 'selectedPlan'));
    }
}
