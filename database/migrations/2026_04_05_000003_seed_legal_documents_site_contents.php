<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $rows = [
            [
                'key' => 'legal.terms_and_conditions',
                'value' => $this->termsAndConditions(),
                'type' => 'html',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'legal.terms_and_conditions_version',
                'value' => '1',
                'type' => 'text',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'legal.membership_contract',
                'value' => $this->membershipContract(),
                'type' => 'html',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'legal.membership_contract_version',
                'value' => '1',
                'type' => 'text',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'legal.liability_waiver',
                'value' => $this->liabilityWaiver(),
                'type' => 'html',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'legal.liability_waiver_version',
                'value' => '1',
                'type' => 'text',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'legal.privacy_policy',
                'value' => $this->privacyPolicy(),
                'type' => 'html',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'legal.privacy_policy_version',
                'value' => '1',
                'type' => 'text',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('site_contents')->insertOrIgnore($row);
        }
    }

    public function down(): void
    {
        DB::table('site_contents')->whereIn('key', [
            'legal.terms_and_conditions',
            'legal.terms_and_conditions_version',
            'legal.membership_contract',
            'legal.membership_contract_version',
            'legal.liability_waiver',
            'legal.liability_waiver_version',
            'legal.privacy_policy',
            'legal.privacy_policy_version',
        ])->delete();
    }

    private function termsAndConditions(): string
    {
        return <<<'HTML'
<h2>Terms &amp; Conditions</h2>
<p>These Terms &amp; Conditions govern your membership and use of the facilities at <strong>AmigosFitnessGym</strong> ("the Gym"). By becoming a member you agree to be bound by these terms.</p>

<h3>1. Membership</h3>
<p>Membership is personal and non-transferable. Your membership is valid for the duration of the plan selected at the time of registration. Access to the Gym is conditional on maintaining an active, paid membership.</p>

<h3>2. Payment &amp; Fees</h3>
<p>Membership fees are due in full at the time of registration. Fees are non-refundable except where required by applicable Philippine law. The Gym reserves the right to modify membership fees with 30 days' written notice.</p>

<h3>3. Member Conduct</h3>
<p>Members must treat staff and other members with respect. Aggressive, disruptive, or unsafe behaviour may result in immediate suspension or termination of membership without refund. Members must follow all posted gym rules and instructions from staff.</p>

<h3>4. Facility Use</h3>
<p>Members use the Gym's equipment and facilities at their own risk. Members are responsible for returning equipment to its proper place after use. The Gym reserves the right to close or restrict access to areas for maintenance, safety, or operational reasons.</p>

<h3>5. Health &amp; Safety</h3>
<p>Members with pre-existing medical conditions are advised to consult a physician before commencing any exercise programme. The Gym is not responsible for injuries arising from failure to disclose relevant health conditions.</p>

<h3>6. Membership Suspension &amp; Termination</h3>
<p>The Gym may suspend or terminate a membership for breach of these Terms. Members who wish to cancel must notify the Gym in writing. No refunds are provided for the unused portion of a membership term.</p>

<h3>7. Governing Law</h3>
<p>These Terms are governed by the laws of the Republic of the Philippines. Any disputes shall be resolved in the appropriate courts of the Philippines.</p>

<h3>8. Amendments</h3>
<p>AmigosFitnessGym reserves the right to amend these Terms at any time. Members will be notified of material changes. Continued use of the Gym after notification constitutes acceptance of the revised Terms.</p>
HTML;
    }

    private function membershipContract(): string
    {
        return <<<'HTML'
<h2>Membership Agreement</h2>
<p>This Membership Agreement ("Agreement") is entered into between <strong>{{gym_name}}</strong> ("the Gym") and the undersigned member:</p>

<table>
<tr><td><strong>Member Name:</strong></td><td>{{member_name}}</td></tr>
<tr><td><strong>Membership Plan:</strong></td><td>{{plan_name}}</td></tr>
<tr><td><strong>Plan Fee:</strong></td><td>{{plan_price}}</td></tr>
<tr><td><strong>Start Date:</strong></td><td>{{start_date}}</td></tr>
</table>

<h3>1. Grant of Membership</h3>
<p>{{gym_name}} grants {{member_name}} a non-exclusive, non-transferable membership to access the Gym's facilities in accordance with the plan selected above. Membership access commences on {{start_date}} and expires at the end of the plan duration.</p>

<h3>2. Member Obligations</h3>
<p>The Member agrees to: (a) pay all applicable membership fees in full prior to access; (b) comply with all Gym rules, policies, and staff instructions; (c) use the Gym's facilities in a safe, responsible manner; and (d) not permit any other person to use their membership credentials.</p>

<h3>3. Fees</h3>
<p>The Member agrees to pay {{plan_price}} for the {{plan_name}} plan. This fee is non-refundable upon activation of the membership. The Gym may introduce new pricing for future membership terms with 30 days' notice.</p>

<h3>4. Assumption of Risk</h3>
<p>The Member acknowledges that physical exercise carries inherent risks including, but not limited to, physical injury. The Member assumes full responsibility for any injury, loss, or damage sustained while using the Gym's facilities, except to the extent caused by the Gym's gross negligence or wilful misconduct.</p>

<h3>5. Termination</h3>
<p>{{gym_name}} reserves the right to terminate this Agreement immediately if the Member breaches any provision herein. In such event, no refund of membership fees shall be provided.</p>

<h3>6. Entire Agreement</h3>
<p>This Agreement, together with the Gym's Terms &amp; Conditions, Liability Waiver, and Privacy Policy, constitutes the entire agreement between the parties regarding the subject matter herein and supersedes all prior agreements or understandings.</p>

<p><em>By proceeding with registration, {{member_name}} acknowledges reading, understanding, and agreeing to this Membership Agreement.</em></p>
HTML;
    }

    private function liabilityWaiver(): string
    {
        return <<<'HTML'
<h2>Liability Waiver &amp; Assumption of Risk</h2>
<p>Please read this Liability Waiver carefully before proceeding. By accepting, you agree to waive certain legal rights.</p>

<h3>1. Acknowledgement of Risk</h3>
<p>I, the Member, acknowledge and understand that participation in physical fitness activities and use of exercise equipment carries inherent risks of injury, illness, or death. These risks include, but are not limited to: muscular strains, sprains, fractures, cardiovascular events, and accidents caused by equipment use or interaction with other members.</p>

<h3>2. Voluntary Participation</h3>
<p>I confirm that my participation in activities at <strong>AmigosFitnessGym</strong> is entirely voluntary. I am aware of the risks involved and freely choose to participate.</p>

<h3>3. Release of Liability</h3>
<p>To the fullest extent permitted by Philippine law, I hereby release, waive, and discharge AmigosFitnessGym, its owners, managers, employees, and agents from any and all liability, claims, demands, or causes of action arising out of or related to my use of the Gym's facilities and equipment, including claims for bodily injury, property damage, or death, whether caused by the negligence of the Gym or otherwise.</p>

<h3>4. Medical Fitness</h3>
<p>I represent that I am in good physical health and know of no medical condition that would prevent me from safely participating in physical fitness activities. I agree to consult a physician before commencing any new exercise programme and to inform Gym staff of any health conditions that may affect my safety.</p>

<h3>5. Emergency Contact &amp; Medical Attention</h3>
<p>I authorise AmigosFitnessGym to seek emergency medical attention on my behalf should I become incapacitated while on the premises. I agree to bear all costs associated with such medical attention.</p>

<h3>6. Indemnification</h3>
<p>I agree to indemnify and hold harmless AmigosFitnessGym from any claims, damages, or expenses (including legal fees) arising from my use of the Gym's facilities or my breach of the Gym's rules or this Waiver.</p>

<p><em>By accepting this Waiver, I confirm that I have read, understood, and voluntarily agreed to its terms.</em></p>
HTML;
    }

    private function privacyPolicy(): string
    {
        return <<<'HTML'
<h2>Privacy Policy &amp; Data Privacy Consent</h2>
<p>This Privacy Policy is issued in compliance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong> of the Philippines and its Implementing Rules and Regulations.</p>

<h3>1. Data Controller</h3>
<p><strong>AmigosFitnessGym</strong> is the Personal Information Controller responsible for the collection, processing, and storage of your personal data.</p>

<h3>2. Personal Data We Collect</h3>
<p>We collect the following personal and sensitive information:</p>
<ul>
<li>Full name, email address, phone number, date of birth</li>
<li>Government-issued identification documents</li>
<li>Payment transaction records (processed via PayMongo; card details are not stored by the Gym)</li>
<li>Membership history and gym access records</li>
<li>Communications with Gym staff via the support chat</li>
</ul>

<h3>3. Purpose of Processing</h3>
<p>Your personal data is collected and processed for the following purposes:</p>
<ul>
<li>Establishing and managing your gym membership</li>
<li>Processing membership fees and payments</li>
<li>Communicating membership status, announcements, and schedule updates</li>
<li>Compliance with legal and regulatory obligations</li>
<li>Ensuring the safety and security of Gym members and staff</li>
</ul>

<h3>4. Legal Basis for Processing</h3>
<p>Processing is based on: (a) the performance of a contract to which you are a party (your Membership Agreement); (b) your freely given, specific, informed, and unambiguous consent; and (c) compliance with legal obligations under Philippine law.</p>

<h3>5. Data Sharing</h3>
<p>Your personal data will not be sold or shared with third parties for marketing purposes. We may share data with: (a) PayMongo, solely for payment processing; (b) law enforcement or regulatory bodies when required by law; and (c) service providers acting on our behalf under appropriate data processing agreements.</p>

<h3>6. Data Retention</h3>
<p>We retain your personal data for the duration of your membership and for a period of five (5) years thereafter, or longer if required by applicable law or for the resolution of disputes.</p>

<h3>7. Your Rights Under RA 10173</h3>
<p>As a data subject, you have the right to: (a) be informed of how your data is processed; (b) access your personal data; (c) correct inaccurate data; (d) object to processing; (e) erasure or blocking of data in certain circumstances; (f) file a complaint with the National Privacy Commission (NPC).</p>
<p>To exercise your rights, contact us at the Gym or through the support chat.</p>

<h3>8. Consent</h3>
<p>By accepting this Privacy Policy, you freely and voluntarily give your informed consent to the collection and processing of your personal data by AmigosFitnessGym as described above. You understand that you may withdraw this consent at any time, subject to legal and contractual restrictions.</p>

<p><em>For privacy concerns, you may contact the National Privacy Commission at <strong>www.privacy.gov.ph</strong>.</em></p>
HTML;
    }
};
