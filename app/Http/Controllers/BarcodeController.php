<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarcodeRequest;
use App\Models\Barcode;
use App\Models\Parameter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Barcode::class, 'barcode');
    }

    public function index(Request $request)
    {
        $filters = [
            'code'       => $request->get('code'),
            'note'       => $request->get('note'),
            'user_id'    => $request->get('user'),
        ];

        $parameterFilters = [
            'subject'    => $request->get('subject'),
            'evaluation' => $request->get('evaluation'),
            'status'     => $request->get('status'),
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

        $users = User::has('barcodes');
        if (Barcode::userCanViewAll()){
            $users = $users->get(['id', 'name', 'surname', 'disabled_at']);
        }else{
            $users = $users->where('department_id', auth()->user()->getAttribute('department_id'))->get(['id', 'name', 'surname', 'disabled_at']);
        }

        $barcodes = Barcode::with('user', 'company')
            ->when(!Barcode::userCanViewAll(), function ($query){
                $query->where('user_id', auth()->id());
            })
            ->when($trashBox, fn($query) => $query->onlyTrashed())
            ->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()])
            ->where(function ($query) use ($filters) {
                foreach ($filters as $column => $value) {
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
            ->latest('created_at')
            ->paginate($limit);

        return view('pages.barcode.index',
            compact(
                'barcodes',
                'statuses',
                'subjects',
                'users',
                'evaluations',
                'trashBox',
                'daterange',
            )
        );
    }

    public function create()
    {
        return view('pages.barcode.edit')
            ->with([
                'method' => 'POST',
                'action' => route('barcode.store'),
                'data'   => new Barcode(),
            ]);
    }

    public function store(BarcodeRequest $request)
    {
        $barcode = auth()->user()->barcodes()->create($request->validated());

        $barcode->parameters()->sync(syncResolver($request->get('parameters') ?? [], 'value'));

        return redirect()->route('barcode.index')->withNotify('info', 'Inquiry');
    }

    public function show(Barcode $barcode)
    {
        return view('pages.barcode.edit')
            ->with([
                'method' => null,
                'action' => null,
                'data'   => $barcode,
            ]);
    }

    public function edit(Barcode $barcode)
    {
        return view('pages.barcode.edit')
            ->with([
                'method' => "PUT",
                'action' => route('barcode.update', $barcode),
                'data'   => $barcode,
            ]);
    }

    public function update(BarcodeRequest $request, Barcode $barcode): RedirectResponse
    {
        $barcode->update(
            array_merge($request->only(['note', 'company_id', 'code', 'mediator_id', 'customer', 'phone']))
        );
        $barcode->parameters()->sync(syncResolver($request->get('parameters') ?? [], 'value'));

        return redirect()->route('barcode.edit', $barcode)->withNotify('info', 'Barcode updated');
    }

    public function destroy(Barcode $barcode)
    {
        return $barcode->delete() ? response('OK') : response('',204);
    }
}
