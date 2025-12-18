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
 * IMPORTANT: This service ONLY handles income (type=2 in database).
 * It NEVER touches or modifies expense transactions (type=1 in database).
 * Database values: 1 = Məxaric (Expense), 2 = Mədaxil (Income)
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

        // Normalize newValue first (before any processing)
        $normalizedNewValue = is_finite((float)$newValue) ? (float)$newValue : 0.0;
        
        // Work must have a client
        if (!$work->client_id) {
            Log::warning('WorkIncomeService: Work has no client_id - skipping transaction creation', [
                'work_id' => $work->id,
                'parameter_id' => $parameterId,
                'new_value' => $normalizedNewValue
            ]);
            // Still update the parameter value, just don't create transaction
            // This ensures payment data is saved even if client_id is missing
            $exists = DB::table('work_parameter')
                ->where('work_id', $work->id)
                ->where('parameter_id', $parameterId)
                ->exists();
            
            if ($exists) {
                DB::table('work_parameter')
                    ->where('work_id', $work->id)
                    ->where('parameter_id', $parameterId)
                    ->update(['value' => (string)$normalizedNewValue]);
            } else {
                DB::table('work_parameter')
                    ->insert([
                        'work_id' => $work->id,
                        'parameter_id' => $parameterId,
                        'value' => (string)$normalizedNewValue
                    ]);
            }
            $work->unsetRelation('parameters');
            return;
        }

        try {
            // Load service relationship to get company_id
            $work->load('service');
            
            // Store old dates before transaction (needed for deletion case)
            $oldPaidAt = $work->paid_at;
            $oldVatDate = $work->vat_date;
            
            DB::transaction(function () use ($work, $parameterId, $normalizedNewValue, $paymentDate, $oldPaidAt, $oldVatDate) {
                
                // STEP 1: Read OLD value from DB BEFORE update (with lock to prevent race conditions)
                $param = WorkParameter::where('work_id', $work->id)
                    ->where('parameter_id', $parameterId)
                    ->lockForUpdate()
                    ->first();

                // Normalize old value to 0 if NULL or invalid (critical for first-time payments)
                $oldValue = 0.0;
                if ($param && isset($param->value) && $param->value !== null && $param->value !== '') {
                    $parsedValue = (float)$param->value;
                    $oldValue = is_finite($parsedValue) ? $parsedValue : 0.0;
                }

                // STEP 2: Create record if it doesn't exist
                if (!$param) {
                    $param = WorkParameter::create([
                        'work_id' => $work->id,
                        'parameter_id' => $parameterId,
                        'value' => '0',
                    ]);
                    $oldValue = 0.0;
                }

                // STEP 3: Update the value in work_parameter table using DB query
                // (Cannot use Eloquent update() on pivot table without primary key)
                DB::table('work_parameter')
                    ->where('work_id', $work->id)
                    ->where('parameter_id', $parameterId)
                    ->update(['value' => (string)$normalizedNewValue]);

                // STEP 4: Calculate delta (both values are normalized, so arithmetic is safe)
                $delta = $normalizedNewValue - $oldValue;

                // DEBUG: Log values to verify delta calculation
                Log::info('WorkIncomeService: Parameter update', [
                    'work_id' => $work->id,
                    'parameter_id' => $parameterId,
                    'old_value' => $oldValue,
                    'new_value' => $normalizedNewValue,
                    'delta' => $delta,
                    'client_id' => $work->client_id,
                ]);

                // STEP 5: Handle transactions based on delta
                // Reload work to get latest paid_at/vat_date values
                $work->refresh();
                
                // Determine payment date: 
                // 1. Use provided paymentDate parameter if given
                // 2. For PAID parameter, use paid_at date (or old paid_at if current is null)
                // 3. For VATPAYMENT parameter, use vat_date date (or old vat_date if current is null)
                // 4. For ILLEGALPAID parameter, use paid_at date (or old paid_at if current is null)
                // 5. Fallback to now() if none available
                if ($paymentDate) {
                    $transactionDate = Carbon::parse($paymentDate)->format('Y-m-d');
                } elseif ($parameterId === Work::PAID) {
                    $transactionDate = ($work->paid_at ? Carbon::parse($work->paid_at) : ($oldPaidAt ? Carbon::parse($oldPaidAt) : null))?->format('Y-m-d');
                } elseif ($parameterId === Work::VATPAYMENT) {
                    $transactionDate = ($work->vat_date ? Carbon::parse($work->vat_date) : ($oldVatDate ? Carbon::parse($oldVatDate) : null))?->format('Y-m-d');
                } elseif ($parameterId === Work::ILLEGALPAID) {
                    $transactionDate = ($work->paid_at ? Carbon::parse($work->paid_at) : ($oldPaidAt ? Carbon::parse($oldPaidAt) : null))?->format('Y-m-d');
                } else {
                    // Fallback: try current dates, then old dates, then now()
                    $transactionDate = ($work->paid_at ? Carbon::parse($work->paid_at) : ($oldPaidAt ? Carbon::parse($oldPaidAt) : null))?->format('Y-m-d')
                        ?? ($work->vat_date ? Carbon::parse($work->vat_date) : ($oldVatDate ? Carbon::parse($oldVatDate) : null))?->format('Y-m-d')
                        ?? now()->format('Y-m-d');
                }
                
                // If still no date found, try to find from existing transactions
                if (!$transactionDate) {
                    $existingTransactionForDate = Transaction::where('client_id', $work->client_id)
                        ->where('source', 'works')
                        ->where('type', '2')
                        ->where('status', (string)Transaction::SUCCESSFUL)
                        ->orderByDesc('transaction_date')
                        ->first();
                    
                    if ($existingTransactionForDate) {
                        $transactionDate = $existingTransactionForDate->transaction_date;
                    } else {
                        $transactionDate = now()->format('Y-m-d');
                    }
                }

                // Get operator (who entered the payment)
                $operatorId = auth()->id();

                if (!$operatorId) {
                    Log::error('WorkIncomeService: No authenticated user', ['work_id' => $work->id]);
                    return;
                }

                // Get company_id from service (work -> service -> company_id)
                $companyId = $work->service_id ? optional($work->service)->company_id : null;
                
                // Load client to get name
                $work->load('client');
                $clientName = $work->client ? $work->client->fullname : 'Naməlum müştəri';
                
                // Find existing transaction for THIS specific work and parameter
                // This allows us to track and delete individual work transactions
                $existingTransaction = Transaction::where('work_id', $work->id)
                    ->where('client_id', $work->client_id)
                    ->where('transaction_date', $transactionDate)
                    ->where('source', 'works')
                    ->where('type', '2') // Mədaxil (Income)
                    ->where('status', (string)Transaction::SUCCESSFUL)
                    ->first();
                
                if ($delta > 0) {
                    // Amount increased: create or update transaction
                    $transactionAmount = is_finite((float)$delta) ? (float)$delta : 0.0;
                    
                    if ($existingTransaction) {
                        // Update existing transaction for this work
                        $existingAmount = (float)$existingTransaction->amount;
                        $newTotalAmount = $existingAmount + $transactionAmount;
                        
                        $note = $clientName . ' üçün ' . number_format($newTotalAmount, 2, '.', ' ') . ' AZN ödəniş edildi';
                        
                        $existingTransaction->update([
                            'amount' => (string)$newTotalAmount,
                            'note' => $note,
                        ]);
                        
                        Log::info('WorkIncomeService: Transaction updated', [
                            'transaction_id' => $existingTransaction->id,
                            'work_id' => $work->id,
                            'parameter_id' => $parameterId,
                            'added_amount' => $transactionAmount,
                            'total_amount' => $newTotalAmount,
                            'transaction_date' => $transactionDate,
                            'client_id' => $work->client_id,
                        ]);
                    } else {
                        // Create new transaction with client name in note
                        $note = $clientName . ' üçün ' . number_format($transactionAmount, 2, '.', ' ') . ' AZN ödəniş edildi';
                        
                        $transaction = Transaction::create([
                            'type' => '2', // 2 = Mədaxil (Income)
                            'source' => 'works',
                            'work_id' => $work->id,
                            'client_id' => $work->client_id,
                            'company_id' => $companyId,
                            'operator_id' => $operatorId,
                            'transaction_date' => $transactionDate,
                            'amount' => (string)$transactionAmount,
                            'currency' => 'AZN',
                            'status' => (string)Transaction::SUCCESSFUL,
                            'user_id' => $operatorId,
                            'note' => $note,
                        ]);
                        
                        Log::info('WorkIncomeService: Income transaction created successfully', [
                            'transaction_id' => $transaction->id,
                            'work_id' => $work->id,
                            'parameter_id' => $parameterId,
                            'delta' => $transactionAmount,
                            'transaction_date' => $transactionDate,
                            'client_id' => $work->client_id,
                        ]);
                    }
                } elseif ($delta < 0) {
                    // Amount decreased: reduce or delete transaction
                    // IMPORTANT: Only delete transactions for THIS specific work_id
                    // Do NOT touch transactions from other works, even if they have same client_id and date
                    $decreaseAmount = abs($delta); // Make it positive for calculation
                    
                    // Find transactions ONLY for this specific work_id
                    // This ensures we don't accidentally delete transactions from other works
                    $existingTransaction = Transaction::where('work_id', $work->id)
                        ->where('client_id', $work->client_id)
                        ->where('source', 'works')
                        ->where('type', '2')
                        ->where('status', (string)Transaction::SUCCESSFUL)
                        ->orderByDesc('transaction_date')
                        ->first();
                    
                    if ($existingTransaction) {
                        $existingAmount = (float)$existingTransaction->amount;
                        
                        // If payment is zeroed out, delete the transaction
                        if ($normalizedNewValue == 0) {
                            $existingTransaction->delete();
                            
                            Log::info('WorkIncomeService: Transaction deleted (payment zeroed)', [
                                'transaction_id' => $existingTransaction->id,
                                'work_id' => $work->id,
                                'parameter_id' => $parameterId,
                                'transaction_amount' => $existingAmount,
                                'transaction_date' => $existingTransaction->transaction_date,
                                'client_id' => $work->client_id,
                            ]);
                        } else {
                            // Payment reduced but not zeroed - reduce transaction amount
                            $newTotalAmount = $existingAmount - $decreaseAmount;
                            
                            if ($newTotalAmount <= 0) {
                                // Delete transaction if amount becomes 0 or negative
                                $existingTransaction->delete();
                                
                                Log::info('WorkIncomeService: Transaction deleted due to payment decrease', [
                                    'transaction_id' => $existingTransaction->id,
                                    'work_id' => $work->id,
                                    'parameter_id' => $parameterId,
                                    'decreased_amount' => $decreaseAmount,
                                    'old_amount' => $existingAmount,
                                    'transaction_date' => $existingTransaction->transaction_date,
                                    'client_id' => $work->client_id,
                                ]);
                            } else {
                                // Update transaction with reduced amount
                                $note = $clientName . ' üçün ' . number_format($newTotalAmount, 2, '.', ' ') . ' AZN ödəniş edildi';
                                
                                $existingTransaction->update([
                                    'amount' => (string)$newTotalAmount,
                                    'note' => $note,
                                ]);
                                
                                Log::info('WorkIncomeService: Transaction reduced due to payment decrease', [
                                    'transaction_id' => $existingTransaction->id,
                                    'work_id' => $work->id,
                                    'parameter_id' => $parameterId,
                                    'decreased_amount' => $decreaseAmount,
                                    'old_amount' => $existingAmount,
                                    'new_amount' => $newTotalAmount,
                                    'transaction_date' => $existingTransaction->transaction_date,
                                    'client_id' => $work->client_id,
                                ]);
                            }
                        }
                    } else {
                        // No existing transaction found for this work_id - log warning
                        Log::warning('WorkIncomeService: Cannot decrease transaction - no existing transaction found for this work', [
                            'work_id' => $work->id,
                            'parameter_id' => $parameterId,
                            'delta' => $delta,
                            'transaction_date' => $transactionDate,
                            'client_id' => $work->client_id,
                            'new_value' => $normalizedNewValue,
                        ]);
                    }
                } else {
                    // Delta is 0 - no change
                    Log::info('WorkIncomeService: Delta is zero, no transaction change', [
                        'work_id' => $work->id,
                        'parameter_id' => $parameterId,
                        'delta' => $delta,
                        'old_value' => $oldValue,
                        'new_value' => $normalizedNewValue,
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

}
