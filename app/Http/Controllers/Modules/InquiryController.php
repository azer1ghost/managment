<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Models\Company;
use App\Models\Inquiry;
use App\Models\Option;
use App\Models\Parameter;
use App\Models\User;
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
        $filters = [
            'code'       => $request->get('code'),
            'note'       => $request->get('note'),
            'company_id' => $request->get('company') == null ? [] : explode(',', $request->get('company')),
            'user_id'    => $request->get('user'),
            'is_out'     => $request->get('is_out'),
        ];

        $parameterFilters = [
            'subject' => [],
            'status' => [],
            'contact_method' => [],
            'source' => [],
            'search_client' => null
        ];
        $limit  = $request->get('limit', 25);

        foreach ($parameterFilters as $key => $filter){
            if($key == 'search_client'){
                $parameterFilters[$key] = $request->get($key);
            }else{
                if($request->get($key) != null){
                    $parameterFilters[$key] = explode(',', $request->get($key));
                }
            }
        }

        $trashBox = $request->has('trash-box');

        if($request->has('daterange')){
            $daterange = $request->get('daterange');
        }else{
            $daterange = now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d');
        }

        [$from, $to] = explode(' - ', $daterange);

        $subjects  =  Parameter::where('name', 'subject')->first()->options->unique();
        $contact_methods = Parameter::where('name', 'contact_method')->first()->options->unique();
        $sources  = Parameter::where('name', 'source')->first()->options->unique();
        $statuses  = Parameter::where('name', 'status')->first()->options->unique();
        $companies = Company::whereNotIn('id', [1])->get();
        $users = User::has('inquiries')->get(['id', 'name', 'surname', 'disabled_at']);

        $inquiries = Inquiry::with('user', 'company')
            ->withoutBackups()
            ->when(!Inquiry::userCanViewAll(), function ($query){
                $query->whereHas('editableUsers', function ($query){
                    $query->where('user_id', auth()->id());
                });
            })
            ->when($trashBox, fn($query) => $query->onlyTrashed())
            ->whereBetween('datetime', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->where(function ($query) use ($filters) {
                foreach ($filters as $column => $value) {
                    if ($column == 'is_out'){
                        if(!is_null($value)){
                            $query->where($column, $value);
                        }
                    } else{
                        $query->when($value, function ($query, $value) use ($column) {
                            if (is_array($value)) {
                                $query->whereIn($column, $value);
                            } else {
                                $query->where($column, 'LIKE',  "%$value%");
                            }
                        });
                    }
                }
            })
            ->where(function ($query) use ($parameterFilters) {
                foreach ($parameterFilters as $column => $value) {
                    $query->when($value, function ($query) use ($column, $value){
                        $query->whereHas('parameters', function ($query) use ($column, $value) {
                            if (is_array($value)) {
                                $parameter_id = Parameter::where('name', $column)->first()->getAttribute('id');
                                $query->where('parameter_id', $parameter_id)->whereIn('value', $value);
                            } else {
                                $query->where('value',   'LIKE', "%" . phone_cleaner($value) . "%")
                                    ->orWhere('value', 'LIKE', "%" . trim($value) . "%");
                            }
                        });
                    });
                }
            })
            ->with([
                'company' => function ($query){
                    $query->select('id', 'name');
                }
            ])
            ->latest('datetime')
            ->paginate($limit);

        return view('panel.pages.inquiry.index',
            compact(
                'inquiries',
                'subjects',
                'statuses',
                'contact_methods',
                'sources',
                'companies',
                'users',
                'trashBox',
                'daterange'
            )
        );
    }

    public function createTask($inquiry): RedirectResponse
    {
       return redirect()->route('tasks.create', ['inquiry_id' => $inquiry]);
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
                    'datetime' => $request->get('date')." ".$request->get('time'),
                    'department_id' => auth()->user()->getAttribute('department_id')
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
                $request->only(['note', 'company_id', 'is_out']),
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

    public function updateStatus(Request $request)
    {
        $data = $request->all();

        $inquiry = Inquiry::find($data['inquiryId']);

        if($data['oldVal'] && $data['newVal']){
            $inquiry->parameters()
                ->updateExistingPivot(Inquiry::STATUS_PARAMETER, ['value' => $data['newVal']]);
        } else if (!$data['oldVal']){
            $inquiry->parameters()
                ->attach(Inquiry::STATUS_PARAMETER, ['value' => $data['newVal']]);
        } else if (!$data['newVal']){
            $inquiry->parameters()
                ->detach(Inquiry::STATUS_PARAMETER);
        }

        $oldOption = $data['oldVal'] ? Option::where('id', $data['oldVal'])->first()->getAttribute('text') : __('translates.filters.select');
        $newOption = $data['newVal'] ? Option::where('id', $data['newVal'])->first()->getAttribute('text') : __('translates.filters.select');

        return json_encode(['data' => [
            'type'    => 'blue',
            'title'   =>  __('translates.flash_messages.inquiry_status_updated.title', ['code' => $inquiry->getAttribute('code')]),
            'message' =>  __('translates.flash_messages.inquiry_status_updated.msg',   ['prev' => $oldOption, 'next' => $newOption])]
        ]);
    }

    public function editAccessToUser(Inquiry $inquiry)
    {
        abort_if(! auth()->user()->hasPermission('editAccessToUser-inquiry'), 403);

        return view('panel.pages.inquiry.access')->with([
           'inquiry' => $inquiry
        ]);
    }

    public function updateAccessToUser(Request $request, Inquiry $inquiry)
    {
        abort_if(! $request->user()->hasPermission('editAccessToUser-inquiry'), 403);

        $editableUsers = [];

        $editableUsersLogs = [];

        foreach ($request->get('users') ?? [] as $editable) {
            $editableUsers[$editable['user_id']] = ['editable_ended_at' => $editable['editable_ended_at']];
            $log = [];
            $log['user_id'] = $request->user()->getAttribute('id');
            $log['action'] = __FUNCTION__;
            $log['message'] = "User #{$request->user()->getAttribute('id')} updated access for {$inquiry->getAttribute('code')}";
            $log['data'] = json_encode(['access' => "#".$request->user()->getAttribute('id') . " gave access to #{$editable['user_id']}"]);

            $editableUsersLogs[] = $log;
        }

        $inquiry->editableUsers()->sync($editableUsers);

        $inquiry->logs()->createMany($editableUsersLogs);

        return back()->withNotify('info', $inquiry->getAttribute('code'));
    }

    public function editableMassAccessUpdate(Request $request)
    {
        $data = $request->all();

        foreach ($data['inquiries'] as $inquiry_id) {
            $inquiry = Inquiry::find($inquiry_id);

            if($inquiry->editableUsers->count() > 1){
                foreach ($inquiry->editableUsers as $editable){
                    $inquiry->editableUsers()->updateExistingPivot($editable->id, ['editable_ended_at' => $data['editable-date']]);
                }
            }else{
                $inquiry->editableUsers()->sync([$inquiry->user_id => ['editable_ended_at' => $data['editable-date']]]);
            }
        }

        return back();
    }

    public function logs(Inquiry $inquiry)
    {
        return view('panel.pages.inquiry.logs')->with([
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
