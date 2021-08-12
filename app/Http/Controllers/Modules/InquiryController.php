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

    public function index()
    {
        return view('panel.pages.customer-services.inquiry.index');
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
        auth()->user()->inquiries()->create(
            array_merge(
                $request->validated(),
                [
                    'code' => $this->createCode(),
                    'datetime' => $request->get('date')." ".$request->get('time')
                ]
            )
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


    /**
     * Update the specified resource in storage.
     *
     * 1. flip key and values of inquiry columns
     * 2. set all attributes values to null and set user_id to current user ID
     * 3. Merge this attributes with validated inputs
     * 4. Convert result collection to Array
     *
     */

    public function update(InquiryRequest $request, Inquiry $inquiry): RedirectResponse
    {
        $backup = $inquiry->replicate()->getAttributes();

        $inquiry->update(
            $inquiry
                ->getColumns()
                ->flip()
                ->map(
                    function ($name, $key) use ($inquiry) {
                        switch ($key) {
                            case "user_id":
                                return auth()->id();
                            case "code":
                                return $inquiry->getAttribute('code');
                            default:
                                return null;
                        }
                    }
                )
                ->merge(
                    array_merge(
                        $request->validated(),
                        ['datetime' => $request->get('date')." ".$request->get('time')]
                    )
                )
                ->toArray()
        );

        if ($inquiry->getChanges()) {
            $backup['code'] = null;
            $inquiry->backups()->create($backup);
        }

        return redirect()->route('inquiry.index')->withNotify('info', 'Inquiry Updated');
    }

    public function destroy(Inquiry $inquiry)
    {
        return $inquiry->delete() ? response('OK') : response('',204);
    }

    public function restore(Inquiry $inquiry)
    {
        return $inquiry->restore() ? response('OK') : response('',204);
    }

    public function forceDelete(Inquiry $inquiry)
    {
        return $inquiry->forceDelete() ? response('OK') : response('',204);
    }

    public function versionRestore(Inquiry $inquiry, Request $request)
    {
        return $inquiry->update(Inquiry::find($request->get('backup_id'))->replicate()->getAttributes())
            ? response('OK')
            : response('',204);
    }
}
