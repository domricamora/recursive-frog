<?php

namespace App\Http\Requests;

use App\Models\Lead;
use App\Models\ServiceTier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Validation for the Find Your Tier form (plan.md #22, #35). */
class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'clinic_name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'string', 'email:filter', 'max:190'],
            'phone' => ['required', 'string', 'max:40', 'regex:/^[0-9+()\-\s()]{6,40}$/'],
            'city' => ['required', 'string', 'max:120'],
            'website' => ['required', 'string', 'max:255'],
            'preferred_contact' => ['required', Rule::in(['email', 'phone', 'messenger', 'viber', 'whatsapp'])],

            'challenge' => ['required', 'string', 'max:1000'],
            'booking_process' => ['required', 'string', 'max:1000'],
            'inquiry_process' => ['required', 'string', 'max:1000'],
            'software_usage' => ['nullable', 'string', 'max:1000'],

            'tier_interest' => ['required', Rule::in([
                Lead::TIER_NOT_SURE,
                'recursive-3', 'recursive-5', 'recursive-7',
            ])],
            'message' => ['nullable', 'string', 'max:3000'],
            'consent' => ['accepted'],

            // Marketing attribution.
            'source_url' => ['nullable', 'string', 'max:500'],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
            'utm_term' => ['nullable', 'string', 'max:120'],
            'utm_content' => ['nullable', 'string', 'max:120'],

            // Honeypot: real visitors never see or fill this.
            'company_fax' => ['prohibited'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'clinic_name' => 'clinic name',
            'preferred_contact' => 'preferred contact method',
            'tier_interest' => 'tier interest',
            'company_fax' => 'company fax',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'website.required' => 'Please enter your clinic website, or type "None" if you do not have one yet.',
            'phone.regex' => 'Please enter a valid phone number.',
            'consent.accepted' => 'Please confirm we may contact you about your inquiry.',
            'company_fax.prohibited' => 'Submission rejected.',
        ];
    }

    /** @return array<string, mixed> */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => trim((string) $this->input('first_name')),
            'last_name' => trim((string) $this->input('last_name')),
            'clinic_name' => trim((string) $this->input('clinic_name')),
            'city' => trim((string) $this->input('city')),
            'website' => trim((string) $this->input('website')),
            'email' => mb_strtolower(trim((string) $this->input('email'))),
        ]);
    }

    /** Human label for the selected tier, resolved against live records. */
    public function tierLabel(): string
    {
        $value = (string) $this->input('tier_interest');

        if ($value === Lead::TIER_NOT_SURE) {
            return 'Not sure yet';
        }

        return ServiceTier::query()
            ->where('slug', $value)
            ->value('name') ?? $value;
    }
}
