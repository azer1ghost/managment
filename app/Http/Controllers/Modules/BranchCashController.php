<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\BranchCash;
use App\Models\BranchCashItem;
use App\Models\Department;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchCashController extends Controller
{
    /**
     * Filial kassa əsas ekranı (filter + gündəlik kassa).
     */
    public function index(Request $request)
    {
        $this->middleware('auth');

        // Tarix gəlməsə, bugünün tarixi ilə işləyək
        $date = $request->input('date') ?: now()->toDateString();
        $departmentId = (int) $request->input('department_id', 13); // default HNBGI

        // Yalnız kassa üçün nəzərdə tutulan departamentlər
        $departments = Department::whereIn('id', [11, 12, 13])->get();

        /** @var BranchCash $branchCash */
        $branchCash = BranchCash::where('department_id', $departmentId)
            ->whereDate('date', $date)
            ->first();

        if (!$branchCash) {
            $branchCash = $this->createBranchCash($departmentId, $date);
        }

        $branchCash->load('items.work', 'department');

        $incomeItems = $branchCash->items->where('direction', 'income');
        $expenseItems = $branchCash->items->where('direction', 'expense');

        $incomeSum = $incomeItems->sum('amount');
        $expenseSum = $expenseItems->sum('amount');

        return view('pages.finance.branch-cash', compact(
            'departments',
            'branchCash',
            'incomeItems',
            'expenseItems',
            'incomeSum',
            'expenseSum',
            'date',
            'departmentId'
        ));
    }

    /**
     * Seçilmiş gün + filial üçün kassa başlığını yaradıb qaytarır.
     */
    protected function createBranchCash(int $departmentId, ?string $date): BranchCash
    {
        $date = $date ?: now()->toDateString();
        // Bir əvvəlki günün son qalıq dəyərini tap
        $previous = BranchCash::where('department_id', $departmentId)
            ->whereDate('date', '<', $date)
            ->orderBy('date', 'desc')
            ->first();

        $opening = $previous ? (float) $previous->closing_balance : 0.0;

        return BranchCash::create([
            'department_id'      => $departmentId,
            'date'               => $date,
            'opening_balance'    => $opening,
            'operations_balance' => 0,
            'handover_amount'    => 0,
            'closing_balance'    => $opening,
            'created_by'         => Auth::id(),
        ]);
    }

    /**
     * Seçilmiş kassa gününə works-dən avtomatik mədaxil sətrlərini əlavə et.
     */
    public function syncFromWorks(Request $request)
    {
        $request->validate([
            'branch_cash_id' => ['required', 'integer', 'exists:branch_cashes,id'],
        ]);

        /** @var BranchCash $branchCash */
        $branchCash = BranchCash::with('items')->findOrFail($request->input('branch_cash_id'));

        $date = $branchCash->date->toDateString();
        $departmentId = $branchCash->department_id;

        // Eyni gündə, eyni departamentdə, ödəniş tarixi uyğun olan işlər
        $works = Work::where('department_id', $departmentId)
            ->whereDate('paid_at', $date)
            ->with('client', 'service')
            ->get();

        DB::transaction(function () use ($works, $branchCash) {
            foreach ($works as $work) {
                // Əgər bu iş artıq kassada varsa, təkrarlamayaq
                $existing = $branchCash->items()
                    ->where('work_id', $work->id)
                    ->first();

                $amountPaid = (float) ($work->getParameterValue(Work::PAID) ?? 0);
                $vatPaid = (float) ($work->getParameterValue(Work::VATPAYMENT) ?? 0);
                $illegalPaid = (float) ($work->getParameterValue(Work::ILLEGALPAID) ?? 0);
                $bankCharge = (float) ($work->bank_charge ?? 0);

                $totalAmount = $amountPaid + $vatPaid + $illegalPaid + $bankCharge;

                if ($totalAmount <= 0) {
                    continue;
                }

                $description = trim(
                    ($work->client->fullname ?? '') .
                    ' - ' .
                    ($work->service->name ?? '')
                );

                if ($existing) {
                    $existing->update([
                        'description' => $description,
                        'amount'      => $totalAmount,
                    ]);
                } else {
                    $branchCash->items()->create([
                        'work_id'     => $work->id,
                        'direction'   => 'income',
                        'description' => $description,
                        'gb'          => (int) ($work->getParameter($work::GB) ?? 0),
                        'price'       => $totalAmount,
                        'amount'      => $totalAmount,
                        'note'        => $work->code,
                    ]);
                }
            }

            $branchCash->refresh();
            $branchCash->recalculateTotals();
        });

        return redirect()
            ->back()
            ->with('success', 'Günlük işlər kassaya yükləndi.');
    }

    /**
     * Manual mədaxil / məxaric sətri əlavə et.
     */
    public function storeItem(Request $request, BranchCash $branchCash)
    {
        $data = $request->validate([
            'direction'    => ['required', 'in:income,expense'],
            'description'  => ['nullable', 'string', 'max:255'],
            'gb'           => ['nullable', 'integer'],
            'representative' => ['nullable', 'integer'],
            'sb'           => ['nullable', 'integer'],
            'price'        => ['nullable', 'numeric'],
            'amount'       => ['required', 'numeric'],
            'note'         => ['nullable', 'string', 'max:255'],
        ]);

        $branchCash->items()->create($data);
        $branchCash->recalculateTotals();

        return redirect()
            ->back()
            ->with('success', 'Kassa sətiri əlavə olundu.');
    }

    /**
     * Təhvil verildi / digər başlıq sahələrinin yenilənməsi.
     */
    public function updateHeader(Request $request, BranchCash $branchCash)
    {
        $data = $request->validate([
            'opening_balance'    => ['nullable', 'numeric'],
            'handover_amount'    => ['nullable', 'numeric'],
        ]);

        $branchCash->fill($data);
        $branchCash->save();
        $branchCash->recalculateTotals();

        return redirect()
            ->back()
            ->with('success', 'Kassa məlumatları yeniləndi.');
    }
}

