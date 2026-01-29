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
        } else {
            // Kassa artıq varsa, opening_balance-in düzgün olduğunu yoxla
            // (əvvəlki gündə item silinibsə, opening_balance yenilənməlidir)
            $previous = BranchCash::where('department_id', $departmentId)
                ->whereDate('date', '<', $date)
                ->orderBy('date', 'desc')
                ->first();

            if ($previous) {
                $previous->recalculateTotals();
                $previous->refresh();
                // Əgər opening_balance köhnədirsə, yenilə
                if (abs($branchCash->opening_balance - $previous->closing_balance) > 0.01) {
                    $branchCash->opening_balance = $previous->closing_balance;
                    $branchCash->recalculateTotals();
                }
            }
        }

        $branchCash->load('items.work', 'department');

        // Ekranda və cəmlərdə yalnız NƏĞD (payment_method = 1) sətirlər görsənsin
        $incomeItems = $branchCash->items
            ->where('direction', 'income')
            ->where('payment_method', 1);
        $expenseItems = $branchCash->items
            ->where('direction', 'expense')
            ->where('payment_method', 1);

        // Ümumi cəmlər də yalnız nəğd üzrə hesablansın
        $incomeCash = $incomeItems->sum('amount'); // Nəğd gəlir cəmi
        $expenseCash = $expenseItems->sum('amount'); // Nəğd xərc cəmi

        $incomeSum = $incomeCash;
        $expenseSum = $expenseCash;

        // Bank, PBank və digər mənbələr üçün cəmlər 0 olsun (artıq göstərmirik)
        $incomeBank = 0;
        $incomePBank = 0;
        $incomeOther = 0;

        $expenseBank = 0;
        $expensePBank = 0;
        $expenseOther = 0;

        return view('pages.finance.branch-cash', compact(
            'departments',
            'branchCash',
            'incomeItems',
            'expenseItems',
            'incomeSum',
            'expenseSum',
            'incomeCash',
            'incomeBank',
            'incomePBank',
            'incomeOther',
            'expenseCash',
            'expenseBank',
            'expensePBank',
            'expenseOther',
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

        // Əgər əvvəlki gün varsa, closing_balance-i yenidən hesabla (item silinibsə düzgün olsun)
        if ($previous) {
            $previous->recalculateTotals();
            $previous->refresh();
            $opening = (float) $previous->closing_balance;
        } else {
            $opening = 0.0;
        }

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

            // Köhnə avtomatik sətirləri silək ki, yenidən sıfırdan hesablayaq
            // (work_id-si olan bütün sətirlər silinir, manual yazılanlara toxunmuruq)
            DB::transaction(function () use ($branchCash) {
                $branchCash->items()
                    ->whereNotNull('work_id')
                    ->delete();
            });

            // Eyni gündə, eyni departamentdə:
            //  - ÖDƏNİŞ METODU NƏĞD OLAN (payment_method = 1)
            //  - E-QAİMƏ TARİXİ YAZILMAMIŞ (invoiced_date IS NULL)
            //
            // İki qrup işi kassaya yükləyirik:
            //  1) YARANMA TARİXİ (created_at) kassanın tarixinə bərabər olan, HƏLƏ ÖDƏNİLMƏMİŞ işlər (debitor siyahısı)
            //  2) ÖDƏNİŞ TARİXİ (paid_at) kassanın tarixinə bərabər olan, artıq ÖDƏNMİŞ işlər (ödənmiş məbləğlər)
            // whereDate istifadə edək ki, tarixin yalnız gün hissəsini müqayisə edək
            $unpaidWorks = Work::where('department_id', $departmentId)
                ->where('payment_method', 1) // yalnız nəğd üzrə debitorlar
                ->whereNull('paid_at')
                ->whereNull('invoiced_date')
                ->whereDate('created_at', $date)
                ->with('client', 'service')
                ->get();

            $paidWorks = Work::where('department_id', $departmentId)
                ->where('payment_method', 1) // yalnız nəğd üzrə nəğd ödənişlər
                ->whereNotNull('paid_at')
                ->whereNull('invoiced_date')
                ->whereDate('paid_at', $date)
                ->with('client', 'service')
                ->get();

            // Hər iki qrupu birləşdir
            $works = $unpaidWorks->merge($paidWorks);

            Log::info('syncFromWorks başladı', [
                'branch_cash_id' => $branchCash->id,
                'date' => $date,
                'department_id' => $departmentId,
                'works_count' => $works->count(),
            ]);

            $itemsCreated = 0;
            $itemsUpdated = 0;
            $itemsSkipped = 0;

            DB::transaction(function () use ($works, $branchCash, $date, &$itemsCreated, &$itemsUpdated, &$itemsSkipped) {
            foreach ($works as $work) {
                // Əgər bu iş artıq kassada varsa, təkrarlamayaq
                $existing = $branchCash->items()
                    ->where('work_id', $work->id)
                    ->first();

                // İşin cari kassaya hansı məqsədlə düşdüyünü müəyyən et:
                //  - Əgər paid_at NULL-dursa -> debitor siyahısı (tam BORC məbləği)
                //  - Əgər paid_at kassanın tarixinə bərabərdirsə -> ödəniş (faktiki ÖDƏNİLMİŞ məbləğ)
                if ($work->paid_at && $work->paid_at->toDateString() === $date) {
                    // ÖDƏNİLMİŞ MƏBLƏĞ (PAID + VATPAYMENT + ILLEGALPAID)
                    $amount = (float) ($work->getParameterValue(Work::PAID) ?? 0);
                    $vat = (float) ($work->getParameterValue(Work::VATPAYMENT) ?? 0);
                    $illegalAmount = (float) ($work->getParameterValue(Work::ILLEGALPAID) ?? 0);
                } else {
                    // DEBITOR (MƏBLƏĞ + ƏDV + DİGƏR (ILLEGAL) tam borc kimi)
                    // ödənilmiş hissələr (PAID, VATPAYMENT və s.) nəzərə alınmır.
                    $amount = (float) ($work->getParameterValue(Work::AMOUNT) ?? 0);
                    $vat = (float) ($work->getParameterValue(Work::VAT) ?? 0);
                    $illegalAmount = (float) ($work->getParameterValue(Work::ILLEGALAMOUNT) ?? 0);
                }

                $totalAmount = $amount + $vat + $illegalAmount;

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

                // Payment method-u work-dən götür (1=Nəğd, 2=Bank, 3=PBank)
                $paymentMethod = $work->payment_method ?? 1; // Default: Nəğd

                if ($existing) {
                    $existing->update([
                        'description' => $description,
                        'amount'      => $totalAmount,
                        'representative' => (int) ($work->getParameterValue(Work::SERVICECOUNT) ?? 0), // parameter_id=20 (Say)
                        'payment_method' => $paymentMethod,
                    ]);
                    $itemsUpdated++;
                    Log::info('Kassa sətri yeniləndi', [
                        'work_id' => $work->id,
                        'item_id' => $existing->id,
                        'amount' => $totalAmount,
                        'payment_method' => $paymentMethod,
                    ]);
                } else {
                    $item = $branchCash->items()->create([
                        'work_id'     => $work->id,
                        'direction'   => 'income',
                        'payment_method' => $paymentMethod,
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
                        'payment_method' => $paymentMethod,
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
            'payment_method' => ['nullable', 'integer', 'in:1,2,3'], // 1=Nəğd, 2=Bank, 3=PBank
            'description'  => ['nullable', 'string', 'max:255'],
            'gb'           => ['nullable', 'integer'],
            'representative' => ['nullable', 'integer'],
            'amount'       => ['required', 'numeric'],
            'note'         => ['nullable', 'string', 'max:255'],
        ]);

        // Default: Nəğd (1)
        if (!isset($data['payment_method'])) {
            $data['payment_method'] = 1;
        }

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

        // Item silindikdən sonra, sonrakı günlərin opening_balance-lərini yenilə
        $this->updateSubsequentDaysOpeningBalance($branchCash);

        return redirect()
            ->back()
            ->with('success', 'Kassa sətiri silindi.');
    }

    /**
     * Verilmiş kassadan sonrakı günlərin opening_balance-lərini yenilə.
     * (Item silindikdə closing_balance dəyişir, sonrakı günlərin opening_balance də dəyişməlidir)
     */
    protected function updateSubsequentDaysOpeningBalance(BranchCash $branchCash): void
    {
        // Bu gündən sonrakı günlərin kassalarını tap
        $subsequent = BranchCash::where('department_id', $branchCash->department_id)
            ->whereDate('date', '>', $branchCash->date)
            ->orderBy('date', 'asc')
            ->get();

        // Hər sonrakı gün üçün, əvvəlki günün closing_balance-indən opening_balance-i yenilə
        foreach ($subsequent as $nextCash) {
            $previous = BranchCash::where('department_id', $branchCash->department_id)
                ->whereDate('date', $nextCash->date->copy()->subDay()->toDateString())
                ->first();

            if ($previous) {
                // Əvvəlki günün closing_balance-ini yenidən hesabla
                $previous->recalculateTotals();
                $previous->refresh();
                
                // Bu günün opening_balance-ini əvvəlki günün closing_balance-indən götür
                $nextCash->opening_balance = $previous->closing_balance;
                $nextCash->recalculateTotals();
            }
        }
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

