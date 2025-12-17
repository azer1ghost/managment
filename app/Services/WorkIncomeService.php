<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Work;
use App\Models\WorkParameter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Isolated service for creating income transactions from work_parameters updates.
 * 
 * IMPORTANT: This service ONLY handles income (type=Transaction::INCOME).
 * It NEVER touches or modifies expense transactions (type=Transaction::EXPENSE).
 * 
 * Income is created ONLY when payment parameters (35/36/37) are UPDATED in work_parameter table
 * and the delta (new_value - old_value) is positive.
 * 
 * CRITICAL: Old value MUST be read BEFORE update, otherwise delta will always be 0.
 */
class WorkIncomeService
{
    /**
     * Update work_parameter and create income transaction if delta > 0.
     * 
     * This method:
     * 1. Reads OLD value from work_parameter table BEFORE update
     * 2. Updates the value in work_parameter table
     * 3. Calculates delta = new_value - old_value
     * 4. Creates income transaction if delta > 0
     * 
     * @param Work $work The work being updated
     * @param int $parameterId The parameter ID (35=PAID, 36=VATPAYMENT, 37=ILLEGALPAID)
     * @param float $newValue The new value from request
     * @param string|null $paymentDate Payment date from request (Y-m-d format) or null to use now()
     * @return void
     */
    public function updateParameterAndCreateIncome(
        Work $work, 
        int $parameterId, 
        float $newValue, 
        ?string $paymentDate = null
    ): void {
        // Only process payment parameters
        if (!in_array($parameterId, [Work::PAID, Work::VATPAYMENT, Work::ILLEGALPAID])) {
            Log::info('WorkIncomeService: Not a payment parameter', ['parameter_id' => $parameterId]);
            return;
        }

        // Work must have a client
        if (!$work->client_id) {
            Log::warning('WorkIncomeService: Work has no client_id', ['work_id' => $work->id]);
            return;
        }

        try {
            DB::transaction(function () use ($work, $parameterId, $newValue, $paymentDate) {
                // STEP 1: Read OLD value from DB BEFORE update (with lock to prevent race conditions)
                $param = WorkParameter::where('work_id', $work->id)
                    ->where('parameter_id', $parameterId)
                    ->lockForUpdate()
                    ->first();

                $oldValue = $param ? (float)($param->value ?? 0) : 0;

                // STEP 2: Create record if it doesn't exist
                if (!$param) {
                    $param = WorkParameter::create([
                        'work_id' => $work->id,
                        'parameter_id' => $parameterId,
                        'value' => '0',
                    ]);
                    $oldValue = 0;
                }

                // STEP 3: Update the value in work_parameter table
                $param->update(['value' => (string)$newValue]);

                // STEP 4: Calculate delta
                $delta = $newValue - $oldValue;

                // DEBUG: Log values to verify delta calculation
                Log::info('WorkIncomeService: Parameter update', [
                    'work_id' => $work->id,
                    'parameter_id' => $parameterId,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'delta' => $delta,
                    'client_id' => $work->client_id,
                ]);

                // STEP 5: Create income transaction ONLY if delta > 0
                if ($delta > 0) {
                    // Reload work to get latest paid_at/vat_date values
                    $work->refresh();
                    
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

                    if (!$operatorId) {
                        Log::error('WorkIncomeService: No authenticated user', ['work_id' => $work->id]);
                        return;
                    }

                    // Create income transaction with delta amount
                    // Note: type and status are stored as strings in DB (per migration)
                    $transaction = Transaction::create([
                        'type' => (string)Transaction::INCOME, // Convert to string as per DB schema
                        'source' => 'works',
                        'work_id' => $work->id,
                        'client_id' => $work->client_id,
                        'operator_id' => $operatorId,
                        'transaction_date' => $transactionDate,
                        'amount' => (string)$delta,
                        'currency' => 'AZN',
                        'status' => (string)Transaction::SUCCESSFUL, // Convert to string as per DB schema
                        'user_id' => $operatorId,
                        'note' => $this->getParameterNote($parameterId) . ' - Delta: ' . number_format($delta, 2),
                    ]);

                    Log::info('WorkIncomeService: Income transaction created successfully', [
                        'transaction_id' => $transaction->id,
                        'work_id' => $work->id,
                        'parameter_id' => $parameterId,
                        'delta' => $delta,
                        'transaction_date' => $transactionDate,
                        'client_id' => $work->client_id,
                    ]);
                } else {
                    Log::info('WorkIncomeService: Delta is not positive, skipping transaction', [
                        'work_id' => $work->id,
                        'parameter_id' => $parameterId,
                        'delta' => $delta,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ]);
                }
            });

            // Clear work's parameter relation cache so it reflects the new value
            $work->unsetRelation('parameters');
        } catch (\Exception $e) {
            Log::error('WorkIncomeService: Exception occurred', [
                'work_id' => $work->id,
                'parameter_id' => $parameterId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
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
