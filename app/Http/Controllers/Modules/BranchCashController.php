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
use Illuminate\Support\Facades\Log;

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
        try {
            $request->validate([
                'branch_cash_id' => ['required', 'integer', 'exists:branch_cashes,id'],
            ]);

            /** @var BranchCash $branchCash */
            $branchCash = BranchCash::with('items')->findOrFail($request->input('branch_cash_id'));

            $date = $branchCash->date->toDateString();
            $departmentId = $branchCash->department_id;

            // Eyni gündə, eyni departamentdə, ödəniş tarixi (paid_at) uyğun olan işlər
            // whereDate istifadə edək ki, tarixin yalnız gün hissəsini müqayisə edək
            $works = Work::where('department_id', $departmentId)
                ->whereNotNull('paid_at')
                ->whereDate('paid_at', $date)
                ->with('client', 'service')
                ->get();

            Log::info('syncFromWorks başladı', [
                'branch_cash_id' => $branchCash->id,
                'date' => $date,
                'department_id' => $departmentId,
                'works_count' => $works->count(),
            ]);

            $itemsCreated = 0;
            $itemsUpdated = 0;
            $itemsSkipped = 0;

            DB::transaction(function () use ($works, $branchCash, &$itemsCreated, &$itemsUpdated, &$itemsSkipped) {
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
                    $itemsSkipped++;
                    continue;
                }

                $description = trim(
                    ($work->client->fullname ?? '') .
                    ' - ' .
                    ($work->service->name ?? '')
                );

                // Bəzi mənbələrdən gələn adlarda səhv kodlaşdırılmış simvollar ola bilir (Incorrect string value xətası).
                // DB-yə insert etməzdən əvvəl UTF-8 olmayan baytları səssizcə ataq.
                $description = $this->sanitizeUtf8($description);

                if ($existing) {
                    $existing->update([
                        'description' => $description,
                        'amount'      => $totalAmount,
                        'representative' => (int) ($work->getParameterValue(Work::SERVICECOUNT) ?? 0), // parameter_id=20 (Say)
                    ]);
                    $itemsUpdated++;
                    Log::info('Kassa sətri yeniləndi', [
                        'work_id' => $work->id,
                        'item_id' => $existing->id,
                        'amount' => $totalAmount,
                    ]);
                } else {
                    $item = $branchCash->items()->create([
                        'work_id'     => $work->id,
                        'direction'   => 'income',
                        'description' => $description,
                        'gb'          => (int) ($work->getParameterValue(Work::GB) ?? 0),
                        'representative' => (int) ($work->getParameterValue(Work::SERVICECOUNT) ?? 0), // parameter_id=20 (Say)
                        'amount'      => $totalAmount,
                        'note'        => $work->code,
                    ]);
                    $itemsCreated++;
                    Log::info('Yeni kassa sətri yaradıldı', [
                        'work_id' => $work->id,
                        'item_id' => $item->id,
                        'amount' => $totalAmount,
                        'description' => $description,
                    ]);
                }
            }

            $branchCash->refresh();
            // Items relation-u yenilə (yeni yaradılan items görünsün)
            $branchCash->load('items');
            $branchCash->recalculateTotals();
        });

            Log::info('syncFromWorks tamamlandı', [
                'branch_cash_id' => $branchCash->id,
                'items_created' => $itemsCreated,
                'items_updated' => $itemsUpdated,
                'items_skipped' => $itemsSkipped,
            ]);

            $message = sprintf(
                'Günlük işlər kassaya yükləndi. Yeni: %d, Yenilənmiş: %d, Atlandı: %d',
                $itemsCreated,
                $itemsUpdated,
                $itemsSkipped
            );

            return redirect()
                ->back()
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('syncFromWorks xətası', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Xəta baş verdi: ' . $e->getMessage());
        }
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
     * Kassa sətirini sil (yalnız manual əlavə edilənlər - work_id null olanlar).
     */
    public function deleteItem(BranchCash $branchCash, BranchCashItem $item)
    {
        // Yalnız manual əlavə edilən sətrləri silməyə icazə ver (work_id null)
        if ($item->work_id !== null) {
            return redirect()
                ->back()
                ->with('error', 'Bu sətir işlərdən avtomatik yüklənib, silinə bilməz.');
        }

        // Item bu kassaya aid olduğunu yoxla
        if ($item->branch_cash_id !== $branchCash->id) {
            abort(403, 'Bu sətir bu kassaya aid deyil.');
        }

        $item->delete();
        $branchCash->recalculateTotals();

        return redirect()
            ->back()
            ->with('success', 'Kassa sətiri silindi.');
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

    /**
     * Malformed UTF-8 simvolları sakitcə təmizlə (MySQL 1366 xətasının qarşısını almaq üçün).
     */
    protected function sanitizeUtf8(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        // UTF-8 olmayan baytları atmaq üçün iconv istifadə edirik.
        $clean = @iconv('UTF-8', 'UTF-8//IGNORE', $value);

        return $clean === false ? $value : $clean;
    }
}

