<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Inquiry::class, 'inquiry');
    }

    protected function generateCode($prefix = 'MG'): string
    {
        return $prefix.str_pad(random_int(0, 999999), 6, 0, STR_PAD_LEFT);
    }

    protected function createCode(): string
    {
        $code = $this->generateCode();
        return Inquiry::select('code')->where('code', $code)->exists() ? $this->createCode() : $code;
    }

    public function index(Request $request)
    {
        return view('panel.pages.customer-services.inquiry.index')->with([
            'trashBox' => $request->has('trash-box')
        ]);
    }

    public function create()
    {
        return view('panel.pages.customer-services.inquiry.edit')
            ->with([
                'method' => 'POST',
                'action' => route('inquiry.store'),
                'data'   => null
            ]);
    }

    public function store(InquiryRequest $request): RedirectResponse
    {
        $inquiry = auth()->user()->inquiries()->create(
            array_merge(
                $request->validated(),
                [
                    'code' => $this->createCode(),
                    'datetime' => $request->get('date')." ".$request->get('time')
                ]
            )
        );

        auth()->user()
            ->editableInquiries()
            ->attach(
                $inquiry->getAttribute('id'),
                ['editable_ended_at' => $inquiry->getAttribute('created_at')->addMinutes(7)]
            );

        return redirect()->route('inquiry.index')->withNotify('info', 'Inquiry');
    }

    public function show(Inquiry $inquiry)
    {
        return view('panel.pages.customer-services.inquiry.edit')
            ->with([
                'method' => null,
                'action' => null,
                'data'   => $inquiry
            ]);
    }

    public function edit(Inquiry $inquiry)
    {
        return view('panel.pages.customer-services.inquiry.edit')
            ->with([
                'method' => "PUT",
                'action' => route('inquiry.update', $inquiry),
                'data'   => $inquiry
            ]);
    }

    public function update(InquiryRequest $request, Inquiry $inquiry): RedirectResponse
    {
        // TODO backup creator / restorer/ for all structure
        $inquiry->update(
            array_merge(
                $request->only(['company_id'],
                ['datetime' => "{$request->get('date')} {$request->get('time')}"]
            )
        ));

        $inquiry->parameters()->sync(syncResolver($request->get('parameters'), 'value'));

        return redirect()->route('inquiry.index')->withNotify('info', 'Inquiry Updated');
    }

    public function destroy(Inquiry $inquiry)
    {
        return $inquiry->delete() ? response('OK') : response('',204);
    }

    public function restore($id)
    {
        $inquiry = Inquiry::onlyTrashed()->findOrFail($id);

        $inquiry->restore();

        return redirect()->route('inquiry.index')->withNotify('info', "Inquiry {$inquiry->getAttribute('code')} restored");
    }

    public function forceDelete($id)
    {
        return Inquiry::onlyTrashed()->find($id)->forceDelete() ? response('OK') : response('',204);
    }

    public function versionRestore(Inquiry $inquiry, Request $request)
    {
        return $inquiry->update(Inquiry::find($request->get('backup_id'))->replicate(['code'])->getAttributes())
            ? response('OK')
            : response('',204);
    }
}
