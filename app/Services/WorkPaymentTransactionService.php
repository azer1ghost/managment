<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WorkPaymentTransactionService
{
    /**
     * Generate income transactions from work payments
     * ONLY from PAID values (PAID, VATPAYMENT, ILLEGALPAID)
     * Groups by client_id + payment_date and sums all paid amounts
     * 
     * IMPORTANT: This ONLY creates INCOME transactions. Expenses are NEVER touched.
     * 
     * @param int|null $clientId Optional: specific client to process
     * @param string|null $date Optional: specific date to process
     * @return void
     */
    public function syncIncomeTransactionsFromWorks($clientId = null, $date = null)
    {
        DB::beginTransaction();
        
        try {
            // Get all works with payments (paid_at or vat_date)
            // ONLY works that have actual PAID values
            $query = Work::with(['parameters', 'client'])
                ->whereNotNull('client_id')
                ->where(function($q) {
                    $q->whereNotNull('paid_at')
                      ->orWhereNotNull('vat_date');
                });
            
            if ($clientId) {
                $query->where('client_id', $clientId);
            }
            
            if ($date) {
                $query->where(function($q) use ($date) {
                    $q->whereDate('paid_at', $date)
                      ->orWhereDate('vat_date', $date);
                });
            }
            
            $works = $query->get();
            
            // Group by client_id and payment date
            // ONLY sum PAID values (not AMOUNT, VAT, ILLEGALAMOUNT)
            $groupedPayments = [];
            
            foreach ($works as $work) {
                $workClientId = $work->client_id;
                
                if (!$workClientId) {
                    continue;
                }
                
                // Get ONLY paid values (not amounts due)
                $paid = (float)($work->getParameterValue(Work::PAID) ?? 0);
                $vatPaid = (float)($work->getParameterValue(Work::VATPAYMENT) ?? 0);
                $illegalPaid = (float)($work->getParameterValue(Work::ILLEGALPAID) ?? 0);
                
                // Skip if no payments at all
                if ($paid <= 0 && $vatPaid <= 0 && $illegalPaid <= 0) {
                    continue;
                }
                
                // Process main payment (paid_at date)
                if ($work->paid_at && ($paid > 0 || $illegalPaid > 0)) {
                    $paymentDate = Carbon::parse($work->paid_at)->format('Y-m-d');
                    $key = $workClientId . '_' . $paymentDate;
                    
                    if (!isset($groupedPayments[$key])) {
                        $groupedPayments[$key] = [
                            'client_id' => $workClientId,
                            'date' => $paymentDate,
                            'total_amount' => 0,
                            'works' => [],
                            'created_by' => auth()->id() ?? $work->user_id ?? null,
                        ];
                    }
                    
                    // Sum ONLY paid values
                    $groupedPayments[$key]['total_amount'] += $paid + $illegalPaid;
                    $groupedPayments[$key]['works'][] = $work->id;
                }
                
                // Process VAT payment (vat_date) - separate if different date
                if ($work->vat_date && $vatPaid > 0) {
                    $vatPaymentDate = Carbon::parse($work->vat_date)->format('Y-m-d');
                    $paidAtDate = $work->paid_at ? Carbon::parse($work->paid_at)->format('Y-m-d') : null;
                    
                    // If VAT date is same as paid_at, merge into existing transaction
                    if ($paidAtDate === $vatPaymentDate && isset($groupedPayments[$workClientId . '_' . $vatPaymentDate])) {
                        // Merge VAT payment into existing transaction
                        $groupedPayments[$workClientId . '_' . $vatPaymentDate]['total_amount'] += $vatPaid;
                        if (!in_array($work->id, $groupedPayments[$workClientId . '_' . $vatPaymentDate]['works'])) {
                            $groupedPayments[$workClientId . '_' . $vatPaymentDate]['works'][] = $work->id;
                        }
                    } else {
                        // Create separate VAT transaction
                        $vatKey = $workClientId . '_vat_' . $vatPaymentDate;
                        
                        if (!isset($groupedPayments[$vatKey])) {
                            $groupedPayments[$vatKey] = [
                                'client_id' => $workClientId,
                                'date' => $vatPaymentDate,
                                'total_amount' => 0,
                                'works' => [],
                                'created_by' => auth()->id() ?? $work->user_id ?? null,
                            ];
                        }
                        
                        $groupedPayments[$vatKey]['total_amount'] += $vatPaid;
                        $groupedPayments[$vatKey]['works'][] = $work->id;
                    }
                }
            }
            
            // Create or update ONLY income transactions
            // CRITICAL: NEVER touch expense transactions (type=EXPENSE or source != 'works')
            foreach ($groupedPayments as $key => $paymentData) {
                if ($paymentData['total_amount'] <= 0) {
                    continue;
                }
                
                // Find existing INCOME transaction for this client + date + source
                // CRITICAL: Only look for income transactions from works source
                // NEVER touch expense transactions (type=EXPENSE or source != 'works')
                $existingTransaction = Transaction::where('client_id', $paymentData['client_id'])
                    ->where(function($q) use ($paymentData) {
                        $q->where('transaction_date', $paymentData['date'])
                          ->orWhere(function($subQ) use ($paymentData) {
                              // Also check created_at if transaction_date is null
                              $subQ->whereNull('transaction_date')
                                   ->whereDate('created_at', $paymentData['date']);
                          });
                    })
                    ->where('source', 'works') // MUST be from works
                    ->where('type', Transaction::INCOME) // MUST be income (1)
                    ->first();
                
                if ($existingTransaction) {
                    // SAFETY CHECK: Verify it's actually an income transaction from works
                    // This prevents accidentally updating expense transactions
                    if ($existingTransaction->type != Transaction::INCOME || $existingTransaction->source != 'works') {
                        \Log::warning('Attempted to update non-income transaction - skipping', [
                            'transaction_id' => $existingTransaction->id,
                            'type' => $existingTransaction->type,
                            'source' => $existingTransaction->source,
                            'expected_type' => Transaction::INCOME,
                            'expected_source' => 'works'
                        ]);
                        continue; // Skip this transaction - it's not an income from works
                    }
                    
                    // Update existing income transaction
                    // CRITICAL: Only update amount, user_id, and note - NEVER change type or source
                    $existingTransaction->update([
                        'amount' => $paymentData['total_amount'],
                        'user_id' => $paymentData['created_by'],
                        'note' => 'Mədaxil - İşlərdən ödənişlər (İş ID-ləri: ' . implode(', ', array_unique($paymentData['works'])) . ')',
                    ]);
                } else {
                    // Create new income transaction
                    // CRITICAL: Always set type=INCOME (1) and source='works'
                    // NEVER create expense transactions here
                    Transaction::create([
                        'client_id' => $paymentData['client_id'],
                        'transaction_date' => $paymentData['date'],
                        'type' => Transaction::INCOME, // ALWAYS income (1)
                        'amount' => $paymentData['total_amount'],
                        'currency' => 'AZN',
                        'source' => 'works', // ALWAYS from works
                        'status' => Transaction::SUCCESSFUL,
                        'user_id' => $paymentData['created_by'],
                        'note' => 'Mədaxil - İşlərdən ödənişlər (İş ID-ləri: ' . implode(', ', array_unique($paymentData['works'])) . ')',
                    ]);
                }
            }
            
            // Remove income transactions that should no longer exist (zero payments)
            // ONLY remove income transactions from works source
            if ($clientId && $date) {
                $hasPayments = isset($groupedPayments[$clientId . '_' . $date]) || 
                              isset($groupedPayments[$clientId . '_vat_' . $date]);
                
                if (!$hasPayments) {
                    Transaction::where('client_id', $clientId)
                        ->where('transaction_date', $date)
                        ->where('source', 'works')
                        ->where('type', Transaction::INCOME) // ONLY income
                        ->delete();
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error syncing income transactions from works: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Recalculate ONLY income transactions for a specific work after payment changes
     * Expenses are NEVER recalculated or touched
     * 
     * @param Work $work
     * @return void
     */
    public function recalculateWorkTransactions(Work $work)
    {
        if (!$work->client_id) {
            return;
        }
        
        $dates = [];
        
        // Get dates from payment fields (paid_at, vat_date)
        if ($work->paid_at) {
            $dates[] = Carbon::parse($work->paid_at)->format('Y-m-d');
        }
        
        if ($work->vat_date) {
            $vatDate = Carbon::parse($work->vat_date)->format('Y-m-d');
            if (!in_array($vatDate, $dates)) {
                $dates[] = $vatDate;
            }
        }
        
        // Also check original values if work was updated (for date changes)
        if ($work->wasChanged('paid_at') && $work->getOriginal('paid_at')) {
            $originalDate = Carbon::parse($work->getOriginal('paid_at'))->format('Y-m-d');
            if (!in_array($originalDate, $dates)) {
                $dates[] = $originalDate;
            }
        }
        
        if ($work->wasChanged('vat_date') && $work->getOriginal('vat_date')) {
            $originalVatDate = Carbon::parse($work->getOriginal('vat_date'))->format('Y-m-d');
            if (!in_array($originalVatDate, $dates)) {
                $dates[] = $originalVatDate;
            }
        }
        
        // Recalculate ONLY income transactions for each date
        // Expenses are NEVER touched
        foreach ($dates as $date) {
            $this->syncIncomeTransactionsFromWorks($work->client_id, $date);
        }
    }
    
    /**
     * Remove ONLY income transactions when work payment is deleted
     * Expenses are NEVER removed or touched
     * 
     * @param Work $work
     * @return void
     */
    public function removeWorkTransactions(Work $work)
    {
        if (!$work->client_id) {
            return;
        }
        
        $dates = [];
        
        // Get dates from payment fields before deletion
        if ($work->paid_at) {
            $dates[] = Carbon::parse($work->paid_at)->format('Y-m-d');
        }
        
        if ($work->vat_date) {
            $vatDate = Carbon::parse($work->vat_date)->format('Y-m-d');
            if (!in_array($vatDate, $dates)) {
                $dates[] = $vatDate;
            }
        }
        
        // Recalculate ONLY income transactions (will remove if total becomes zero)
        // Expenses are NEVER touched
        foreach ($dates as $date) {
            $this->syncIncomeTransactionsFromWorks($work->client_id, $date);
        }
    }
    
    /**
     * Sync ALL income transactions from all works
     * Useful for initial setup or bulk recalculation
     * Expenses are NEVER touched
     * 
     * @return void
     */
    public function syncAllIncomeTransactions()
    {
        // Get all unique client+date combinations from works with payments
        $works = Work::with(['parameters', 'client'])
            ->whereNotNull('client_id')
            ->where(function($q) {
                $q->whereNotNull('paid_at')
                  ->orWhereNotNull('vat_date');
            })
            ->get();
        
        $clientDates = [];
        
        foreach ($works as $work) {
            if (!$work->client_id) {
                continue;
            }
            
            $paid = (float)($work->getParameterValue(Work::PAID) ?? 0);
            $vatPaid = (float)($work->getParameterValue(Work::VATPAYMENT) ?? 0);
            $illegalPaid = (float)($work->getParameterValue(Work::ILLEGALPAID) ?? 0);
            
            if ($paid <= 0 && $vatPaid <= 0 && $illegalPaid <= 0) {
                continue;
            }
            
            if ($work->paid_at) {
                $date = Carbon::parse($work->paid_at)->format('Y-m-d');
                $clientDates[$work->client_id][$date] = true;
            }
            
            if ($work->vat_date) {
                $date = Carbon::parse($work->vat_date)->format('Y-m-d');
                $clientDates[$work->client_id][$date] = true;
            }
        }
        
        // Sync each client+date combination
        foreach ($clientDates as $clientId => $dates) {
            foreach (array_keys($dates) as $date) {
                $this->syncIncomeTransactionsFromWorks($clientId, $date);
            }
        }
    }
}

