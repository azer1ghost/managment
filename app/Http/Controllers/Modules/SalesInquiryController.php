<?php

namespace App\Http\Controllers\Modules;

use App\Services\FirebaseApi;
use App\Models\{Department, Inquiry, Parameter, User};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;


class SalesInquiryController extends Controller
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
            'user_id'    => $request->get('user'),
            'is_out'     => $request->get('is_out'),
            'client_id'  => $request->get('client_id'),
        ];

        $parameterFilters = [
            'subject'    => $request->get('subject'),
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
        $subjects  = Parameter::where('name', 'subject')->first()->options->unique();
        $evaluations  = Parameter::where('name', 'evaluation')->first()->options->unique();
        $departmentIds = [Department::SALES, Department::BUSINESS];
        $users = User::has('inquiries')->whereIn('department_id', $departmentIds)->get(['id', 'name', 'surname', 'disabled_at']);

        $inquiries = Inquiry::with('user', 'company', 'client')
            ->when(app()->environment('production'), fn($q) => $q->whereIn('department_id', $departmentIds)->where('client_id', '!=', null))
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
                },
            ])
            ->latest('datetime')
            ->paginate($limit);

        return view('pages.inquiry-sales.index',
            compact(
                'inquiries',
                'statuses',
                'subjects',
                'evaluations',
                'trashBox',
                'daterange',
                'users',
            )
        );
    }

    public function potentialCustomers(Request $request)
    {
        $filters = [
            'code'       => $request->get('code'),
            'note'       => $request->get('note'),
            'user_id'    => $request->get('user'),
            'is_out'     => $request->get('is_out'),
            'client_id'  => $request->get('client_id'),
        ];

        $parameterFilters = [
            'subject'    => $request->get('subject'),
            'evaluation' => $request->get('evaluation'),
            'status'     => 96,
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
        $subjects  = Parameter::where('name', 'subject')->first()->options->unique();
        $evaluations  = Parameter::where('name', 'evaluation')->first()->options->unique();
        $users = User::has('inquiries')->whereDepartmentId(Department::SALES)->get(['id', 'name', 'surname', 'disabled_at']);

        $inquiries = Inquiry::with('user', 'company', 'client')
            ->when(app()->environment('production'), fn($q) => $q->whereDepartmentId(Department::SALES))
            ->withoutBackups()
//            ->when(!Inquiry::userCanViewAll(), function ($query){
//                if (Inquiry::userCanViewAllDepartment()){
//                    $query->where('department_id', auth()->user()->getAttribute('department_id'));
//                }else{
//                    $query->whereHas('editableUsers', function ($query){
//                        $query->where('user_id', auth()->id());
//                    });
//                }
//            })
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
                },
            ])
            ->latest('datetime')
            ->paginate($limit);

        return view('pages.inquiry-sales.potential-customers',
            compact(
                'inquiries',
                'statuses',
                'subjects',
                'evaluations',
                'trashBox',
                'daterange',
                'users',
            )
        );
    }

}
