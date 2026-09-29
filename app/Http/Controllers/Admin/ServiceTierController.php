<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ServiceFeature;
use App\Models\ServiceTier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Editable service tiers and their features (plan.md #26, #34). */
class ServiceTierController extends Controller
{
    public function index(): View
    {
        return view('admin.services.index', [
            'tiers' => ServiceTier::query()->ordered()->with('features')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.form', ['tier' => new ServiceTier]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tier = ServiceTier::create($this->validated($request));

        AuditLog::record('service.created', $tier, 'Created tier '.$tier->name.'.');

        return redirect()->route('admin.services.edit', $tier)->with('status', 'Tier created.');
    }

    public function edit(ServiceTier $tier): View
    {
        return view('admin.services.form', ['tier' => $tier->load('features')]);
    }

    public function update(Request $request, ServiceTier $tier): RedirectResponse
    {
        $tier->update($this->validated($request, $tier));

        AuditLog::record('service.updated', $tier, 'Updated tier '.$tier->name.'.');

        return back()->with('status', 'Tier saved.');
    }

    public function destroy(ServiceTier $tier): RedirectResponse
    {
        $name = $tier->name;

        AuditLog::record('service.deleted', $tier, 'Deleted tier '.$name.'.');
        $tier->delete();

        return redirect()->route('admin.services.index')->with('status', "{$name} deleted.");
    }

    /* ------------------------------------------------------------------ */
    /* Features — each carries a draft/confirmed/archived status           */
    /* ------------------------------------------------------------------ */

    public function storeFeature(Request $request, ServiceTier $tier): RedirectResponse
    {
        $data = $request->validate($this->featureRules());

        $tier->features()->create([
            ...$data,
            // validate() only returns keys that were actually submitted, so an
            // omitted optional field has to be read defensively.
            'display_order' => ($data['display_order'] ?? null) ?: $tier->features()->max('display_order') + 1,
        ]);

        AuditLog::record('service.feature_created', $tier, 'Added feature "'.$data['name'].'" to '.$tier->name.'.');

        return back()->with('status', 'Feature added.');
    }

    public function updateFeature(Request $request, ServiceTier $tier, ServiceFeature $feature): RedirectResponse
    {
        abort_unless($feature->service_tier_id === $tier->getKey(), 404);

        $feature->update($request->validate($this->featureRules()));

        AuditLog::record('service.feature_updated', $tier, 'Updated feature "'.$feature->name.'" on '.$tier->name.'.');

        return back()->with('status', 'Feature saved.');
    }

    public function destroyFeature(ServiceTier $tier, ServiceFeature $feature): RedirectResponse
    {
        abort_unless($feature->service_tier_id === $tier->getKey(), 404);

        $name = $feature->name;
        $feature->delete();

        AuditLog::record('service.feature_deleted', $tier, 'Removed feature "'.$name.'" from '.$tier->name.'.');

        return back()->with('status', 'Feature removed.');
    }

    /** @return array<string, mixed> */
    protected function featureRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1000'],
            'group' => ['nullable', 'string', 'max:80'],
            'status' => ['required', Rule::in([
                ServiceTier::STATUS_DRAFT, ServiceTier::STATUS_CONFIRMED, ServiceTier::STATUS_ARCHIVED,
            ])],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ];
    }

    /* ------------------------------------------------------------------ */

    /** @return array<string, mixed> */
    protected function validated(Request $request, ?ServiceTier $tier = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['required', 'string', 'max:80', Rule::unique('service_tiers', 'slug')->ignore($tier?->id)],
            'model' => ['nullable', 'string', 'max:80'],
            'includes' => ['nullable', 'string', 'max:120'],
            'badge' => ['nullable', 'string', 'max:80'],
            'summary' => ['nullable', 'string', 'max:500'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'audience' => ['nullable', 'string', 'max:2000'],
            'problems_solved' => ['nullable', 'string', 'max:3000'],
            'implementation' => ['nullable', 'string', 'max:3000'],
            'example_project' => ['nullable', 'string', 'max:2000'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'highlights' => ['nullable', 'array'],
            'highlights.*' => ['string', 'max:120'],
            'display_order' => ['required', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'pricing_published' => ['nullable', 'boolean'],
            'price_label' => ['nullable', 'string', 'max:120'],
        ]);

        $data['slug'] = Str::slug($data['slug']);
        $data['is_active'] = $request->boolean('is_active');
        $data['pricing_published'] = $request->boolean('pricing_published');
        $data['highlights'] = array_values(array_filter(array_map('trim', $data['highlights'] ?? [])));

        return $data;
    }
}

