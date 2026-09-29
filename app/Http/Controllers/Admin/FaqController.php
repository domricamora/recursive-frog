<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** FAQ management (plan.md #26). */
class FaqController extends Controller
{
    public function index(): View
    {
        return view('admin.faqs.index', [
            'faqs' => Faq::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.faqs.form', ['faq' => new Faq]);
    }

    public function store(Request $request): RedirectResponse
    {
        $faq = Faq::create($this->validated($request));

        AuditLog::record('faq.created', $faq, 'Added FAQ: '.$faq->question);

        return redirect()->route('admin.faqs.index')->with('status', 'Question added.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.form', ['faq' => $faq]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validated($request, $faq));

        AuditLog::record('faq.updated', $faq, 'Updated FAQ: '.$faq->question);

        return redirect()->route('admin.faqs.index')->with('status', 'Question saved.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $question = $faq->question;

        AuditLog::record('faq.deleted', $faq, 'Deleted FAQ: '.$question);
        $faq->delete();

        return back()->with('status', 'Question removed.');
    }

    /** @return array<string, mixed> */
    protected function validated(Request $request, ?Faq $faq = null): array
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:250'],
            'answer' => ['required', 'string', 'max:5000'],
            'category' => ['nullable', 'string', 'max:80'],
            'display_order' => ['required', 'integer', 'min:0', 'max:999'],
            'published' => ['nullable', 'boolean'],
        ]);

        $data['published'] = $request->boolean('published');

        return $data;
    }
}
