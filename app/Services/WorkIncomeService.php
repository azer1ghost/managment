<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Work;
use Carbon\Carbon;

/**
 * Isolated service for creating income transactions from work_parameters updates.
 * 
 * IMPORTANT: This service ONLY handles income (type=Transaction::INCOME).
 * It NEVER touches or modifies expense transactions (type=Transaction::EXPENSE).
 * 
 * Income is created ONLY when payment parameters (35/36/37) are UPDATED in work_parameters table
 * and the delta (new_value - old_value) is positive.
 */
class WorkIncomeService
{
    /**
     * Create income transaction when a payment parameter is updated.
     * 
     * This method is called when work_parameters is UPDATED:
     * - parameter_id = 35 (PAID)
     * - parameter_id = 36 (VATPAYMENT)
     * - parameter_id = 37 (ILLEGALPAID)
     * 
     * Creates ONE income transaction per parameter update with delta amount.
     * 
     * @param Work $work The work being updated
     * @param int $parameterId The parameter ID (35=PAID, 36=VATPAYMENT, 37=ILLEGALPAID)
     * @param float $oldValue The previous value from work_parameters table
     * @param float $newValue The new value from request
     * @param string|null $paymentDate Payment date from request (Y-m-d format) or null to use now()
     * @return void
     */
    public function createIncomeFromParameterUpdate(
        Work $work, 
        int $parameterId, 
        float $oldValue, 
        float $newValue, 
        ?string $paymentDate = null
    ): void {
        // Only process payment parameters
        if (!in_array($parameterId, [Work::PAID, Work::VATPAYMENT, Work::ILLEGALPAID])) {
            return;
        }

        // Calculate delta
        $delta = $newValue - $oldValue;

        // Only create income if delta > 0 (real money entered)
        if ($delta <= 0) {
            return;
        }

        // Work must have a client
        if (!$work->client_id) {
            return;
        }

        // Determine payment date: from request OR paid_at OR vat_date OR now()
        $transactionDate = $paymentDate 
            ? Carbon::parse($paymentDate)->format('Y-m-d')
            : ($work->paid_at 
                ? Carbon::parse($work->paid_at)->format('Y-m-d')
                : ($work->vat_date 
                    ? Carbon::parse($work->vat_date)->format('Y-m-d')
                    : now()->format('Y-m-d')));

        // Get operator (who entered the payment)
        $operatorId = auth()->id();

        // Create income transaction with delta amount
        Transaction::create([
            'type' => Transaction::INCOME,
            'source' => 'works',
            'work_id' => $work->id,
            'client_id' => $work->client_id,
            'operator_id' => $operatorId,
            'transaction_date' => $transactionDate,
            'amount' => $delta,
            'currency' => 'AZN',
            'status' => Transaction::SUCCESSFUL,
            'user_id' => $operatorId,
            'note' => $this->getParameterNote($parameterId) . ' - Delta: ' . number_format($delta, 2),
        ]);
    }

    /**
     * Get note text for parameter type.
     * 
     * @param int $parameterId
     * @return string
     */
    private function getParameterNote(int $parameterId): string
    {
        $notes = [
            Work::PAID => 'Mədaxil - Əsas məbləğdən ödəniş',
            Work::VATPAYMENT => 'Mədaxil - ƏDV-dən ödəniş',
            Work::ILLEGALPAID => 'Mədaxil - Digər məbləğdən ödəniş',
        ];

        return $notes[$parameterId] ?? 'Mədaxil - İş ödənişi';
    }
}
