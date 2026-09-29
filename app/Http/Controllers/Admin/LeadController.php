<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

/** Lead management: review, assign, annotate and export (plan.md #26). */
class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $leads = Lead::query()
            ->with('assignee')
            ->status($request->string('status')->toString() ?: null)
            ->when($request->string('tier')->toString(), fn ($q, $tier) => $q->where('tier_interest', $tier))
            ->when($request->string('q')->toString(), function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('clinic_name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.leads.index', [
            'leads' => $leads,
            'statuses' => Lead::statuses(),
            'filters' => $request->only(['status', 'tier', 'q']),
        ]);
    }

    public function show(Lead $lead): View
    {
        AuditLog::record('lead.viewed', $lead, 'Viewed inquiry from '.$lead->clinic_name.'.');

        $lead->load(['notes.author', 'assignee']);

        return view('admin.leads.show', [
            'lead' => $lead,
            'statuses' => Lead::statuses(),
            'team' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Lead::statuses()))],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $lead->update($data);

        AuditLog::record(
            'lead.updated',
            $lead,
            'Set status to '.$lead->statusLabel().(($data['assigned_to'] ?? null) ? ' and assigned an owner.' : '.'),
            $data
        );

        return back()->with('status', 'Lead updated.');
    }

    public function storeNote(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        LeadNote::create([
            'lead_id' => $lead->id,
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        AuditLog::record('lead.note_added', $lead, 'Added an internal note.');

        return back()->with('status', 'Note added.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $clinic = $lead->clinic_name;

        AuditLog::record('lead.deleted', $lead, 'Deleted inquiry from '.$clinic.'.');
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('status', 'Inquiry deleted.');
    }

    /** CSV export honouring the active filters (plan.md #26). */
    public function export(Request $request): StreamedResponse
    {
        $leads = Lead::query()
            ->with('assignee')
            ->status($request->string('status')->toString() ?: null)
            ->when($request->string('tier')->toString(), fn ($q, $tier) => $q->where('tier_interest', $tier))
            ->latest()
            ->get();

        $headers = [
            'Reference', 'Received', 'Status', 'First name', 'Last name', 'Clinic',
            'Email', 'Phone', 'City', 'Website', 'Preferred contact', 'Tier interest',
            'Biggest challenge', 'Bookings', 'Inquiries', 'Software', 'Message',
            'Owner', 'Source URL', 'UTM source', 'UTM medium', 'UTM campaign',
        ];

        $rows = $leads->map(fn (Lead $lead) => [
            'RF-'.str_pad((string) $lead->id, 5, '0', STR_PAD_LEFT),
            $lead->created_at?->toDayDateTimeString(),
            $lead->statusLabel(),
            $lead->first_name,
            $lead->last_name,
            $lead->clinic_name,
            $lead->email,
            $lead->phone,
            $lead->city,
            $lead->website,
            $lead->preferred_contact,
            $lead->tierLabel(),
            $lead->challenge,
            $lead->booking_process,
            $lead->inquiry_process,
            $lead->software_usage,
            $lead->message,
            $lead->assignee?->name,
            $lead->source_url,
            $lead->utm_source,
            $lead->utm_medium,
            $lead->utm_campaign,
        ])->all();

        $filename = 'recursive-frog-leads-'.now()->format('Y-m-d-His').'.csv';

        return response()
            ->streamDownload(function () use ($headers, $rows) {
                $out = fopen('php://output', 'w');
                fwrite($out, "\xEF\xBB\xBF"); // BOM so Excel reads UTF-8
                fputcsv($out, $headers);
                foreach ($rows as $row) {
                    fputcsv($out, $row);
                }
                fclose($out);
            }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
