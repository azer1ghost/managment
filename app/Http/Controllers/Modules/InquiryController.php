<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Models\Inquiry;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Inquiry::class, 'inquiry');
    }

    public function index(Request $request)
    {
        return view('panel.pages.inquiry.index')->with([
            'trashBox' => $request->has('trash-box')
        ]);
    }

    public function create()
    {
        return view('panel.pages.inquiry.edit')
            ->with([
                'method' => 'POST',
                'action' => route('inquiry.store'),
                'data'   => new Inquiry()
            ]);
    }

    public function store(InquiryRequest $request): RedirectResponse
    {
        $inquiry = auth()->user()->inquiries()->create(
            array_merge(
                $request->validated(),
                [
                    'code' => Inquiry::generateCustomCode(),
                    'datetime' => $request->get('date')." ".$request->get('time')
                ]
            )
        );

        $inquiry->parameters()->sync(syncResolver($request->get('parameters'), 'value'));

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
        return view('panel.pages.inquiry.edit')
            ->with([
                'method' => null,
                'action' => null,
                'data'   => $inquiry
            ]);
    }

    public function edit(Inquiry $inquiry)
    {
        return view('panel.pages.inquiry.edit')
            ->with([
                'method' => "PUT",
                'action' => route('inquiry.update', $inquiry),
                'data'   => $inquiry
            ]);
    }

    public function update(InquiryRequest $request, Inquiry $inquiry): RedirectResponse
    {

        $oldInquiry = $inquiry->replicate(['code'])->getAttributes();
        $inquiry->update(
            array_merge(
                $request->only(['note', 'company_id']),
                ['datetime' => Carbon::createFromFormat('d-m-Y H:i', $request->get('date')." ".$request->get('time'))]
            )
        );

        $newParameters = $request->get('parameters');
        $oldParameters = $inquiry->getRelationValue('parameters')->pluck('pivot.value', 'id')->toArray();

        $changedParams = (bool) array_diff($oldParameters, $newParameters);

        if ($inquiry->getChanges() || $changedParams) {
           $backup = $inquiry->backups()->create($oldInquiry);
           $backup->parameters()->sync(syncResolver($oldParameters, 'value'));
        }

        $inquiry->parameters()->sync(syncResolver($newParameters, 'value'));

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
        $old = Inquiry::find($request->get('backup_id'));

        $attributes = $old->replicate(['code'])->getAttributes();

        $parameters = $old->getRelationValue('parameters')->pluck('pivot.value', 'id')->toArray();

        if (
            $inquiry->update($attributes) &&
            $inquiry->parameters()->sync(syncResolver($parameters, 'value'))
        ) {
           return response('OK');
        }

        return response('',204);
    }

}
