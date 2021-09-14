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

    public function store(InquiryRequest $request)
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

        $inquiry->parameters()->sync(syncResolver($request->get('parameters') ?? [], 'value'));

        auth()->user()
            ->editableInquiries()
            ->attach(
                $inquiry->getAttribute('id'),
                ['editable_ended_at' => $inquiry->getAttribute('created_at')->addHours(5)] //->addMinutes(7)
            );


        /* Log created inquiry with params */
        $createdInquiry = Inquiry::with(['parameters' => function($query){
            $query->select('name');
        }])->find($inquiry->getAttribute('id'));

        \Log::channel('daily')->notice("User #" . $request->user()->id . " created new Inquiry. Content is: " . json_encode($createdInquiry));

        $createdInquiry->logs()->create([
            'user_id' => $request->user()->id,
            'action' => 'created',
            'data' => json_encode($createdInquiry),
            'message' => "User #" . $request->user()->id  . " created new Inquiry."
        ]);
        /* End of logging */

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
           $backup->parameters()->sync(syncResolver($oldParameters ?? [], 'value'));

           $inquiry->parameters()->sync(syncResolver($newParameters ?? [], 'value'));

            /* Log created inquiry with params */

            $data['old'] = $backup;
            $data['old']['parameters'] = $oldParameters;

            $data['new'] = $inquiry->getChanges();
            $data['new']['parameters'] = array_diff($oldParameters, $newParameters);

            \Log::channel('daily')->notice("User #" . $request->user()->id . " updated Inquiry (CODE {$inquiry->getAttribute('code')}). Content is: " . json_encode($data));

            $inquiry->logs()->create([
                'user_id' => $request->user()->id,
                'action' => 'updated',
                'data' => json_encode($data),
                'message' => "User #" . $request->user()->id  . " updated Inquiry."
            ]);
            /* End of logging */

        }

        return redirect()->route('inquiry.index')->withNotify('info', 'Inquiry Updated');
    }

    public function giveAccessToUser(Inquiry $inquiry)
    {
        return view('panel.pages.inquiry.access')->with([
           'inquiry' => $inquiry
        ]);
    }

    public function destroy(Inquiry $inquiry)
    {
        \Log::channel('daily')->warning("User #" . auth()->id() . " deleted inquiry (CODE {$inquiry->getAttribute('code')}).");

        $inquiry->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'message' => "User #" . auth()->id() . " deleted inquiry. (CODE {$inquiry->getAttribute('code')})"
        ]);

        return $inquiry->delete() ? response('OK') : response('',204);
    }

    public function restore($id)
    {
        $inquiry = Inquiry::onlyTrashed()->findOrFail($id);

        $inquiry->restore();

        \Log::channel('daily')->alert("User #" . auth()->id() . " restored inquiry (CODE {$inquiry->getAttribute('code')}).");

        $inquiry->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'restored',
            'message' => "User #" . auth()->id() . " restored inquiry. (CODE {$inquiry->getAttribute('code')})"
        ]);

        return redirect()->route('inquiry.index')->withNotify('info', "Inquiry {$inquiry->getAttribute('code')} restored");
    }

    public function forceDelete($id)
    {
        $inquiry = Inquiry::onlyTrashed()->find($id);

        \Log::channel('daily')->alert("User #" . auth()->id() . " force-deleted inquiry (CODE {$inquiry->getAttribute('code')}).");

        $inquiry->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'force-deleted',
            'data' => json_encode($inquiry),
            'message' => "User #" . auth()->id() . " force-deleted inquiry. (CODE {$inquiry->getAttribute('code')})"
        ]);

        return $inquiry->forceDelete() ? response('OK') : response('',204);
    }

    public function versionRestore(Inquiry $inquiry, Request $request)
    {
        $old = Inquiry::find($request->get('backup_id'));

        $attributes = $old->replicate(['code'])->getAttributes();

        $parameters = $old->getRelationValue('parameters')->pluck('pivot.value', 'id')->toArray();

        $inquiryBeforeRestore = $inquiry;

        if (
            $inquiry->update($attributes) &&
            $inquiry->parameters()->sync(syncResolver($parameters ?? [], 'value'))
        ) {

            /* Log created inquiry with params */
            \Log::channel('daily')->notice("User #" . $request->user()->id . " restored to old version Inquiry (CODE {$inquiry->getAttribute('code')}). Content is: " . json_encode($inquiryBeforeRestore));

            $inquiry->logs()->create([
                'user_id' => $request->user()->id,
                'action' => 'version-restored',
                'data' => json_encode($inquiryBeforeRestore),
                'message' => "User #" . $request->user()->id  . " restored to old version Inquiry."
            ]);
            /* End of logging */

            return response('OK');
        }

        return response('',204);
    }

}
