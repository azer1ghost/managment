<?php

namespace App\Http\Controllers\Modules;

use App\Exports\InquiriesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Inquiry;
use App\Models\Option;
use App\Models\Parameter;
use App\Models\Task;
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
            'department_id' => $request->get('department'),
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

        foreach ($parameterFilters as $key => $filter) {
            if($key == 'search_client'){
                $parameterFilters[$key] = $request->get($key);
            }else {
                if($request->get($key) != null) {
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

        $departments  =  Department::has('inquiries')->get(['id', 'name']);
        $subjects = optional(optional(Parameter::where('name', 'subject')->first())->options)->unique() ?? collect();
        $contact_methods = optional(optional(Parameter::where('name', 'contact_method')->first())->options)->unique() ?? collect();
        $sources = optional(optional(Parameter::where('name', 'source')->first())->options)->unique() ?? collect();
        $statuses = optional(optional(Parameter::where('name', 'status')->first())->options)->unique() ?? collect();

        $companies = Company::isInquirable()->get();


        $users = User::has('inquiries');
        if (Inquiry::userCanViewAll()){
            $users = $users->get(['id', 'name', 'surname', 'disabled_at']);
        }else{
            $users = $users->where('department_id', auth()->user()->getAttribute('department_id'))->get(['id', 'name', 'surname', 'disabled_at']);
        }

        $inquiries = Inquiry::with('user', 'company')
            ->when(app()->environment('production'), fn($q) => $q->whereIn('department_id', [Department::CALL_CENTER,Department::SALES,Department::TEST])
            )
            ->withoutBackups()
            ->when(!Inquiry::userCanViewAll(), function ($query){
                if (Inquiry::userCanViewAllDepartment()){
                    $query->where('department_id', auth()->user()->getAttribute('department_id'));
                }else{
                    $query->whereHas('editableUsers', function ($query){
                        $query->where('user_id', auth()->id());
                    });
                }
            })
            ->when($trashBox, fn($query) => $query->onlyTrashed())
            ->whereBetween('datetime', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->where(function ($query) use ($filters) {
                foreach ($filters as $column => $value) {
                    if ($column == 'is_out') {
                        if(!is_null($value)) {
                            $query->where($column, $value);
                        }
                    } else {
                        $query->when($value, function ($query, $value) use ($column) {
                            if(is_numeric($value)) {
                                $query->where($column, $value);
                            } else if (is_array($value)) {
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
            ->latest('datetime');

        if(is_numeric($limit)) {
            $inquiries = $inquiries->paginate($limit);
        }
        else {
            $inquiries = $inquiries->get();
        }

        $types = Inquiry::types();

        return view('pages.inquiry.index',
            compact(
                'inquiries',
                'subjects',
                'statuses',
                'contact_methods',
                'sources',
                'companies',
                'users',
                'trashBox',
                'daterange',
                'departments',
                'types'
            )
        );
    }

    public function createTask(Inquiry $inquiry)
    {
        $inquirySubject = $inquiry->getParameter('subject');

        $subjectKinds = '';

        if(!is_null($inquirySubject) && $inquirySubject->subParameters()->exists()) {
            $subjectKinds = ', ' . $inquirySubject->subParameters->map(
                fn($p) => !is_null($inquiry->getParameter($p->name)) ? ($p->getAttribute('type') == 'select' ?
                    $inquiry->getParameter($p->name)->text :
                    $p->label . ': ' . $inquiry->getParameter($p->name)->value) : '')->implode('name', ',');
        }

        return view('pages.tasks.edit')->with([
            'action' => route('tasks.store'),
            'method' => 'POST',
            'departments' => Department::pluck('name', 'id')->toArray(),
            'inquiry' => $inquiry,
            'data' => new Task(['name' => !is_null($inquirySubject) ? $inquirySubject->getAttribute('text') . rtrim($subjectKinds, ',') : ''])
       ]);
    }

    public function create()
    {
        $backUrl = back()->getTargetUrl();

        return view('pages.inquiry.edit')
            ->with([
                'method' => 'POST',
                'action' => route('inquiry.store'),
                'data'   => new Inquiry(),
                'backUrl'   => $backUrl
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
                    'department_id' => auth()->user()->getAttribute('department_id'),
                    'type' => $request->get('inq_type'),
                ]
            )
        );

        $inquiry->parameters()->sync(syncResolver($request->get('parameters') ?? [], 'value'));

        auth()->user()
            ->editableInquiries()
            ->attach(
                $inquiry->getAttribute('id'),
                ['editable_ended_at' => $inquiry->getAttribute('created_at')->addHours(10)] //->addMinutes(7)
            );

        return redirect()->to($request->get('backUrl'))->withNotify('info', 'Inquiry');
    }

    public function show(Inquiry $inquiry)
    {
        $backUrl = back()->getTargetUrl();

        return view('pages.inquiry.edit')
            ->with([
                'method' => null,
                'action' => null,
                'data'   => $inquiry,
                'backUrl' => $backUrl
            ]);
    }

    public function edit(Inquiry $inquiry)
    {
        $backUrl = back()->getTargetUrl();

        return view('pages.inquiry.edit')
            ->with([
                'method' => "PUT",
                'action' => route('inquiry.update', $inquiry),
                'data'   => $inquiry,
                'backUrl' => $backUrl
            ]);
    }
    public function export()
    {
        return \Excel::download(new InquiriesExport(), 'inquiries.xlsx');
    }

    public function update(InquiryRequest $request, Inquiry $inquiry): RedirectResponse
    {
        $oldInquiry = $inquiry->replicate(['code'])->getAttributes();
        $inquiry->update(
            array_merge(
                $request->only(['note', 'company_id', 'is_out', 'client_name', 'checking', 'alarm']),
                ['datetime' => Carbon::createFromFormat('d-m-Y H:i', $request->get('date')." ".$request->get('time'))]
            )
        );

        $newParameters = $request->get('parameters');
        $oldParameters = $inquiry->getRelationValue('parameters')->pluck('pivot.value', 'id')->toArray();

        $changedParams = array_diff_assoc($newParameters, $oldParameters);

        if ($inquiry->getChanges() || count($changedParams)) {
           $backup = $inquiry->backups()->create($oldInquiry);
           $backup->parameters()->sync(syncResolver($oldParameters ?? [], 'value'));

           $inquiry->parameters()->sync(syncResolver($newParameters ?? [], 'value'));
        }

        return redirect()->to($request->get('backUrl'))->withNotify('info', 'Inquiry updated');
    }

    public function logs(Inquiry $inquiry)
    {
        return view('pages.inquiry.logs')->with([
            'logs' => $inquiry->ledgers()->latest('id')->with('user')->get(),
            'inquiry' => $inquiry
        ]);
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

        return view('pages.inquiry.access')->with([
           'inquiry' => $inquiry
        ]);
    }

    public function updateAccessToUser(Request $request, Inquiry $inquiry)
    {
        abort_if(! $request->user()->hasPermission('editAccessToUser-inquiry'), 403);

        $editableUsers = [];

        foreach ($request->get('users') ?? [] as $editable) {
            $editableUsers[$editable['user_id']] = ['editable_ended_at' => $editable['editable_ended_at']];
        }

        $inquiry->editableUsers()->sync($editableUsers);

        return back()->withNotify('info', $inquiry->getAttribute('code'));
    }

    public function editableMassAccessUpdate(Request $request)
    {
        $data = $request->all();

        foreach ($data['inquiries'] as $inquiry_id) {
            $inquiry = Inquiry::find($inquiry_id);
            $inquiry->editableUsers()->detach();

            foreach ($data['users'] as $user) {
                $inquiry->editableUsers()->attach([$user => ['editable_ended_at' => $data['editable-date']]]);
            }
        }

        return back();
    }

    public function destroy(Inquiry $inquiry)
    {
        return $inquiry->delete() ? response('OK') : response('',204);
    }

    public function restore($id)
    {
        $inquiry = Inquiry::onlyTrashed()->findOrFail($id);

        $inquiry->restore();

        return redirect()->back()->withNotify('info', "Inquiry {$inquiry->getAttribute('code')} restored");
    }

    public function forceDelete($id)
    {
        $inquiry = Inquiry::onlyTrashed()->find($id);

        return $inquiry->forceDelete() ? response('OK') : response('',204);
    }

    public function versionRestore(Inquiry $inquiry, Request $request)
    {
        $old = Inquiry::find($request->get('backup_id'));

        $attributes = $old->replicate(['code'])->getAttributes();

        $parameters = $old->getRelationValue('parameters')->pluck('pivot.value', 'id')->toArray();

        if (
            $inquiry->update($attributes) &&
            $inquiry->parameters()->sync(syncResolver($parameters ?? [], 'value'))
        ) {
            return response('OK');
        }

        return response('',204);
    }
    public function getTaskData($inquiryId)
    {
        $inquiry = Inquiry::findOrFail($inquiryId);

        $task = $inquiry->task()->first();

        return $task;
    }

}
