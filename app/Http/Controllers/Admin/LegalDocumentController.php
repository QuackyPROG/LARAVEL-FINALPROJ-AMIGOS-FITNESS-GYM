<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\View\View;

class LegalDocumentController extends Controller
{
    public const DOCUMENTS = [
        'terms-and-conditions' => [
            'key' => 'legal.terms_and_conditions',
            'title' => 'Terms & Conditions',
        ],
        'membership-contract' => [
            'key' => 'legal.membership_contract',
            'title' => 'Membership Contract',
        ],
        'liability-waiver' => [
            'key' => 'legal.liability_waiver',
            'title' => 'Liability Waiver',
        ],
        'privacy-policy' => [
            'key' => 'legal.privacy_policy',
            'title' => 'Privacy Policy',
        ],
    ];

    public function index(): View
    {
        $documents = collect(self::DOCUMENTS)->map(function (array $doc, string $slug): array {
            return [
                'slug' => $slug,
                'title' => $doc['title'],
                'version' => (int) SiteContent::get($doc['key'].'_version', '1'),
                'updated_at' => SiteContent::where('key', $doc['key'])->value('updated_at'),
            ];
        })->values();

        return view('admin.legal.index', compact('documents'));
    }

    public function edit(string $key): View
    {
        abort_unless(array_key_exists($key, self::DOCUMENTS), 404);

        $doc = self::DOCUMENTS[$key];

        return view('admin.legal.edit', [
            'slug' => $key,
            'title' => $doc['title'],
        ]);
    }
}
