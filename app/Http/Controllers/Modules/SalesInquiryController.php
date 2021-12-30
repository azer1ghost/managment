<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Models\Client;
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
use Illuminate\Validation\Rules\In;

class SalesInquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Inquiry::class, 'inquiry');
    }

    public function __invoke(Request $request)
    {
        $filters = [
            'code'       => $request->get('code'),
            'note'       => $request->get('note'),
            'user_id'    => $request->get('user'),
            'is_out'     => $request->get('is_out'),
            'client'     => $request->get('client'),
        ];

        $parameterFilters = [
            'evaluation' => $request->get('evaluation'),
            'status'     => $request->get('status'),
            'phone'      => $request->get('phone'),
            'qvs'        => $request->get('qvs'),
        ];

        $limit  = $request->get('limit', 25);
        $trashBox = $request->has('trash-box');

        if($request->has('daterange')){
            $daterange = $request->get('daterange');
        }else{
            $daterange = now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d');
        }

        [$from, $to] = explode(' - ', $daterange);

        $statuses  = Parameter::where('name', 'status')->first()->options->unique();
        $evaluations  = Parameter::where('name', 'evaluation')->first()->options->unique();
        $users = User::has('inquiries')->whereDepartmentId(Department::SALES)->get(['id', 'name', 'surname', 'disabled_at']);
        $clients = Client::get(['id', 'fullname', 'voen']);

        $inquiries = Inquiry::with('user', 'company')
            ->whereDepartmentId(3)
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
            ->latest('datetime')
            ->paginate($limit);

        return view('panel.pages.inquiry-sales.index',
            compact(
                'inquiries',
                'statuses',
                'evaluations',
                'trashBox',
                'daterange',
                'users',
                'clients'
            )
        );
    }
}
