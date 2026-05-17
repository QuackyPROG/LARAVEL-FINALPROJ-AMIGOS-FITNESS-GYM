@extends('layouts.public')

@section('title', 'Become a Member')

@section('content')

<style>
    .rg-page {
        position: relative;
        overflow: hidden;
        padding: 48px 24px 72px;
        background:
            linear-gradient(125deg, rgba(0, 0, 0, 0.94) 0%, rgba(0, 0, 0, 0.84) 46%, rgba(13, 13, 13, 0.94) 100%),
            url("{{ asset('images/gym-bg.png') }}") center / cover fixed;
    }

    .rg-page::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 74% 10%, rgba(251, 191, 36, 0.10), transparent 34%);
        pointer-events: none;
    }

    .rg-shell {
        position: relative;
        z-index: 1;
        width: min(720px, 100%);
        margin: 0 auto;
    }

    .rg-label,
    .rg-step-eyebrow {
        display: inline-flex;
        width: fit-content;
        align-items: center;
        gap: 6px;
        color: #fbbf24 !important;
        border: 1px solid rgba(251, 191, 36, 0.28);
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .rg-title {
        margin-top: 12px;
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: clamp(34px, 5vw, 52px);
        font-weight: 900;
        line-height: 0.94;
        letter-spacing: 0;
        text-transform: uppercase;
        color: #fff !important;
    }

    .rg-title span {
        color: #fbbf24 !important;
    }

    .rg-copy {
        margin-top: 10px;
        max-width: 520px;
        color: #747474;
        font-size: 13px;
        line-height: 1.7;
    }

    .rg-header {
        margin-bottom: 20px;
    }

    .rg-note {
        margin-top: 18px;
        color: #585858;
        font-size: 12px;
        line-height: 1.6;
        text-align: center;
    }

    .rg-note a {
        color: #d4d4d4;
        font-weight: 700;
        text-decoration: underline;
        text-decoration-color: rgba(251, 191, 36, 0.40);
        text-underline-offset: 3px;
        transition: color 0.2s, text-decoration-color 0.2s;
    }

    .rg-note a:hover {
        color: #fbbf24;
        text-decoration-color: #fbbf24;
    }

    .rg-panel {
        background: linear-gradient(180deg, rgba(18, 18, 18, 0.92), rgba(8, 8, 8, 0.94));
        border: 1px solid rgba(255, 255, 255, 0.10);
        box-shadow: 0 24px 72px rgba(0, 0, 0, 0.52), 0 0 48px rgba(251, 191, 36, 0.08);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 24px;
    }

    /* ── Wizard ── */
    .rg-wizard {
        color: #fff;
    }

    .rg-progress {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 8px;
        margin: 0 auto 20px;
        max-width: 720px;
        justify-items: center;
        align-items: center;
    }

    .rg-progress-item {
        min-width: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .rg-progress-marker {
        width: 30px;
        height: 30px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.12);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.065), rgba(255, 255, 255, 0.02));
        color: #555;
        font-size: 11px;
        font-weight: 800;
        transition: background 0.2s, border-color 0.2s, color 0.2s;
    }

    .rg-progress-marker svg {
        width: 12px;
        height: 12px;
    }

    .rg-progress-item.is-active .rg-progress-marker {
        border-color: #fbbf24;
        background: rgba(251, 191, 36, 0.10);
        color: #fbbf24;
        box-shadow: 0 0 18px rgba(251, 191, 36, 0.18), inset 0 0 0 1px rgba(251, 191, 36, 0.10);
    }

    .rg-progress-item.is-complete .rg-progress-marker {
        background: #fbbf24;
        border-color: #fbbf24;
        color: #050505;
    }

    .rg-progress-label {
        display: block;
        margin-top: 6px;
        overflow: hidden;
        color: #555;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-overflow: ellipsis;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .rg-progress-item.is-active .rg-progress-label,
    .rg-progress-item.is-complete .rg-progress-label {
        color: #d0d0d0;
    }

    /* ── Card ── */
    .rg-card {
        min-height: 440px;
        border-radius: 12px;
        background:
            radial-gradient(circle at 14% 0%, rgba(251, 191, 36, 0.06), transparent 30%),
            rgba(6, 6, 6, 0.70);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 24px;
    }

    .rg-heading {
        margin-top: 10px;
        margin-bottom: 18px;
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: 28px;
        font-weight: 900;
        line-height: 1;
        letter-spacing: 0;
        text-transform: uppercase;
        color: #fff !important;
    }

    .rg-muted {
        margin-bottom: 4px;
        color: #7a7a7a;
        font-size: 12.5px;
        line-height: 1.65;
    }

    .rg-field-help {
        color: #606060;
        font-size: 11px;
        line-height: 1.45;
    }

    /* ── Fields ── */
    .rg-field-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .rg-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .rg-field--full {
        grid-column: 1 / -1;
    }

    .rg-field label,
    .rg-file-label {
        color: #b0b0b0;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        cursor: pointer;
    }

    .rg-input {
        width: 100%;
        min-height: 42px;
        border: 1px solid rgba(255, 255, 255, 0.10) !important;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.055);
        color: #fff;
        font-size: 14px;
        outline: none;
        padding: 10px 13px;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .rg-input::placeholder {
        color: #404040;
    }

    .rg-input:focus {
        border-color: #fbbf24 !important;
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.10);
    }

    .rg-error {
        color: #fca5a5;
        font-size: 11px;
        line-height: 1.4;
    }

    /* ── Plans ── */
    .rg-plan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
    }

    .rg-plan {
        display: block;
        cursor: pointer;
        border: 1px solid rgba(255, 255, 255, 0.09) !important;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.045);
        padding: 22px;
        transition: transform 0.2s, border-color 0.2s, background 0.2s, box-shadow 0.2s;
        min-height: 190px;
    }

    .rg-plan:hover {
        transform: translateY(-2px);
        border-color: rgba(251, 191, 36, 0.30) !important;
    }

    .rg-plan.is-selected {
        border-color: #fbbf24 !important;
        background: rgba(251, 191, 36, 0.07);
        box-shadow: 0 0 20px rgba(251, 191, 36, 0.10);
    }

    .rg-plan-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .rg-plan-name {
        color: #fff;
        font-size: 14px;
        font-weight: 800;
    }

    .rg-plan-term {
        margin-top: 3px;
        color: #585858;
        font-size: 11px;
    }

    .rg-price {
        color: #fbbf24;
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: 26px;
        font-weight: 900;
        line-height: 1;
        white-space: nowrap;
    }

    .rg-benefits {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-top: 14px;
        color: #7a7a7a;
        font-size: 12px;
        list-style: none;
    }

    .rg-benefits li {
        display: flex;
        gap: 7px;
        line-height: 1.45;
    }

    .rg-benefits li::before {
        content: '✓';
        color: #fbbf24;
        font-weight: 900;
        flex-shrink: 0;
    }

    /* ── Legal Docs ── */
    .rg-docs {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 16px;
    }

    .rg-doc {
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.09) !important;
        border-radius: 12px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.055), rgba(255, 255, 255, 0.022));
        transition: border-color 0.2s;
    }

    .rg-doc:hover {
        border-color: rgba(251, 191, 36, 0.22) !important;
    }

    .rg-doc-title {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.07) !important;
        color: #e0e0e0;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .rg-doc-title::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #fbbf24;
        box-shadow: 0 0 10px rgba(251, 191, 36, 0.50);
        flex: 0 0 auto;
    }

    .rg-doc-body {
        max-height: 120px;
        overflow-y: auto;
        padding: 13px 16px;
        color: #9a9a9a;
        font-size: 12px;
        line-height: 1.70;
        scrollbar-width: thin;
        scrollbar-color: rgba(251, 191, 36, 0.60) rgba(255, 255, 255, 0.04);
    }

    .rg-check {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.07) !important;
        background: rgba(0, 0, 0, 0.28);
        color: #d0d0d0;
        font-size: 12px;
        line-height: 1.5;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }

    .rg-check:hover {
        background: rgba(251, 191, 36, 0.045);
        color: #fff;
    }

    .rg-check input {
        width: 15px;
        height: 15px;
        margin-top: 1px;
        accent-color: #fbbf24;
        flex: 0 0 auto;
    }

    /* ── Upload ── */
    .rg-upload {
        border: 1px dashed rgba(251, 191, 36, 0.38) !important;
        border-radius: 14px;
        background:
            radial-gradient(circle at 90% 0%, rgba(251, 191, 36, 0.08), transparent 30%),
            rgba(251, 191, 36, 0.035);
        padding: 20px;
    }

    .rg-upload-head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .rg-upload-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fbbf24;
        color: #050505;
        box-shadow: 0 8px 20px rgba(251, 191, 36, 0.16);
        flex: 0 0 auto;
    }

    .rg-upload-icon svg {
        width: 18px;
        height: 18px;
    }

    .rg-upload-title {
        color: #fff;
        font-size: 13.5px;
        font-weight: 800;
        margin-bottom: 3px;
    }

    .rg-upload-meta {
        margin-top: 8px;
        color: #787878;
        font-size: 11px;
    }

    .rg-file-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        border-radius: 999px;
        background: rgba(251, 191, 36, 0.09);
        border: 1px solid rgba(251, 191, 36, 0.18);
        color: #efefef;
        padding: 7px 12px;
        font-size: 11px;
    }

    .rg-secure {
        margin-top: 14px;
        border-left: 2px solid #fbbf24;
        background: rgba(255, 255, 255, 0.045);
        border-radius: 8px;
        padding: 12px 14px;
        color: #909090;
        font-size: 12px;
        line-height: 1.6;
    }

    /* ── Review ── */
    .rg-review {
        margin: 16px 0;
        border: 1px solid rgba(251, 191, 36, 0.18) !important;
        border-radius: 12px;
        background:
            linear-gradient(180deg, rgba(251, 191, 36, 0.065), rgba(255, 255, 255, 0.025)),
            rgba(255, 255, 255, 0.025);
        padding: 4px 18px;
    }

    .rg-review-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.07) !important;
    }

    .rg-review-row:last-child {
        border-bottom: 0 !important;
    }

    .rg-review-label {
        color: #787878;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .rg-review-value {
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .rg-review-row.is-total {
        padding: 14px 0;
    }

    .rg-review-row.is-total .rg-review-label {
        color: #fbbf24;
    }

    .rg-review-row.is-total .rg-review-value {
        color: #fbbf24;
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: 28px;
        font-weight: 900;
        line-height: 1;
    }

    .rg-success {
        border: 1px solid rgba(34, 197, 94, 0.24) !important;
        border-radius: 10px;
        background: rgba(34, 197, 94, 0.07);
        color: #a7f3c0;
        padding: 12px 14px;
        font-size: 12px;
        line-height: 1.55;
    }

    /* ── Actions ── */
    .rg-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.07) !important;
    }

    .rg-btn {
        display: inline-flex;
        min-height: 40px;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        border: 1px solid transparent !important;
        padding: 10px 20px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        transition: transform 0.2s, background 0.2s, border-color 0.2s, color 0.2s, box-shadow 0.2s;
    }

    .rg-btn:hover {
        transform: translateY(-1px);
    }

    .rg-btn-primary {
        background: #fbbf24;
        color: #050505;
        box-shadow: 0 8px 22px rgba(251, 191, 36, 0.16);
    }

    .rg-btn-primary:hover {
        background: #f59e0b;
        color: #050505;
        box-shadow: 0 12px 28px rgba(251, 191, 36, 0.24);
    }

    .rg-btn-secondary {
        background: transparent;
        border-color: rgba(255, 255, 255, 0.14) !important;
        color: #c0c0c0;
    }

    .rg-btn-secondary:hover {
        border-color: rgba(251, 191, 36, 0.34) !important;
        color: #fbbf24;
        background: rgba(251, 191, 36, 0.045);
    }

    .rg-btn-wide {
        width: 100%;
        margin-top: 16px;
    }

    [x-cloak] {
        display: none !important;
    }

    /* ── Responsive ── */
    @media (max-width: 640px) {
        .rg-page {
            padding: 32px 14px 56px;
        }

        .rg-panel {
            border-radius: 12px;
            padding: 18px;
        }

        .rg-title {
            font-size: 40px;
        }

        .rg-field-grid,
        .rg-progress {
            grid-template-columns: 1fr;
        }

        .rg-card {
            min-height: auto;
            padding: 18px;
        }

        .rg-review-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }

        .rg-review-value {
            text-align: left;
        }

        .rg-progress-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rg-progress-label {
            margin-top: 0;
        }

        .rg-actions {
            align-items: stretch;
            flex-direction: column;
            gap: 12px;
        }

        .rg-btn {
            width: 100%;
        }
    }
</style>

<section class="rg-page">
    <div class="rg-shell">
        <div class="rg-header">
            <span class="rg-label">Registration</span>
            <h1 class="rg-title">Become a <span>Member</span></h1>
            <p class="rg-copy">
                Fill in your details below to get started. Payment is processed securely via PayMongo.
            </p>
        </div>

        <div class="rg-panel">
            <livewire:public.registration-form :selected-plan-id="$selectedPlanId" />
        </div>

        <p class="rg-note">
            Already a member?
            <a href="{{ route('login') }}">Sign in here</a>
        </p>
    </div>
</section>

@endsection