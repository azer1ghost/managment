<?php

namespace App\Http\Controllers\Modules;

use App\Exports\CreditorsExport;
use App\Exports\WorksExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreditorRequest;
use App\Interfaces\CreditorRepositoryInterface;
use App\Models\Company;
use App\Models\Creditor;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CreditorController extends Controller
{
    protected CreditorRepositoryInterface $creditorRepository;

    public function __construct(CreditorRepositoryInterface $creditorRepository)
    {
        $this->middleware('auth');
        $this->authorizeResource(Creditor::class, 'creditor');
        $this->creditorRepository = $creditorRepository;
    }
    public function export(Request $request)
    {
        $filters = json_decode($request->get('filters'), true);

        return (new CreditorsExport($this->creditorRepository, $filters))->download('creditors.xlsx');
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $filters = [
            'search' => $request->get('search'),
            'company' => $request->get('company_id'),
            'status' => $request->get('status'),
            'supplier' => $request->get('supplier_id'),
        ];
        $creditors = $this->creditorRepository->allFilteredCreditors($filters)->orderBy('status')->paginate($limit);
        return view('pages.creditors.index')->with([
            'companies' => Company::get(['id', 'name', 'logo']),
            'suppliers' => Supplier::get(['id', 'name']),
            'statuses' => Creditor::statuses(),
            'filters' => $filters,
            'creditors' => $creditors,
        ]);
    }

    public function create(Request $request)
    {
        if ($request->get('id')) {

            $data = Creditor::whereId($request->get('id'))->first();
        } else {
            $data = new Creditor();
        }
        return view('pages.creditors.edit')->with([
            'action' => route('creditors.store'),
            'method' => 'POST',
            'data' => $data,
            'companies' => Company::pluck('name', 'id')->toArray(),
            'suppliers' => Supplier::get(['id', 'name', 'voen']),
            'statuses' => Creditor::statuses(),
        ]);
    }

    public function store(CreditorRequest $request)
    {
        $creditor = Creditor::create($request->validated());
        $validated['doc'] = $request->has('doc');
        $validated = $request->validated();

        return redirect()
            ->route('creditors.edit', $creditor)
            ->withNotify('success', $creditor->getAttribute('name'));

    }

    public function show(Creditor $creditor)
    {
        return view('pages.creditors.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $creditor,
            'companies' => Company::pluck('name', 'id')->toArray(),
            'suppliers' => Supplier::pluck('name', 'id')->toArray(),
            'statuses' => Creditor::statuses(),
        ]);
    }

    public function edit(Creditor $creditor)
    {
        return view('pages.creditors.edit')->with([
            'action' => route('creditors.update', $creditor),
            'method' => 'PUT',
            'data' => $creditor,
            'companies' => Company::pluck('name', 'id')->toArray(),
            'suppliers' => Supplier::pluck('name', 'id')->toArray(),
            'statuses' => Creditor::statuses(),
        ]);
    }

    public function update(CreditorRequest $request, Creditor $creditor)
    {
        $validated = $request->validated();
        if ($request->get('status') == 2 && $creditor->getAttribute('paid_at') == null) {
            $validated['paid_at'] = now();
        }
        $validated['doc'] = $request->has('doc');
        $creditor->update($validated);

        return redirect()
            ->route('creditors.edit', $creditor)
            ->withNotify('success', $creditor->getAttribute('name'));
    }

    public function destroy(Creditor $creditor)
    {
        if ($creditor->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

    public function updateAmount(Request $request)
    {
        Creditor::whereId($request->get('id'))->update(['amount' => $request->get('amount')]);

        return response()->json(['message' => 'ok'], 200);
    }

    public function updateVat(Request $request)
    {
        Creditor::whereId($request->get('id'))->update(['vat' => $request->get('vat')]);

        return response()->json(['message' => 'ok'], 200);
    }

    public function updateColor(Request $request)
    {
        Creditor::whereId($request->get('id'))->update(['painted' => $request->get('painted')]);

        return response()->json(['message' => 'ok'], 200);
    }

    public function payment(Request $request)
    {
        $creditor = Creditor::whereId($request->get('id'))->first();
        $creditor->update(['paid' => $creditor->paid + $request->get('paid'), 'vat_paid' => $creditor->vat_paid + $request->get('vat_paid')]);
        if ($creditor->paid + $creditor->vat_paid >= $creditor->amount + $creditor->vat) {
            $creditor->update(['status' => 2]);
        } elseif ($creditor->paid + $creditor->vat_paid < $creditor->amount + $creditor->vat && $creditor->paid + $creditor->vat_paid > 0) {
            $creditor->update(['status' => 3]);
        } else {
            $creditor->update(['status' => 1]);
        }
        $note = '"' . $creditor->getSupplierName() . '" üçün ' . $request->get('paid') + $request->get('vat_paid') . ' AZN ödəniş edildi';
        Transaction::addTransaction(auth()->id(), 0, $request->get('paid') + $request->get('vat_paid'), $creditor->company_id, '', null, 'Creditor', 0, '', $note);
        return back();
    }

}
