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
     * Groups by client_id + payment_date and sums all payments
     * 
     * @param int|null $clientId Optional: specific client to process
     * @param string|null $date Optional: specific date to process
     * @return void
     */
    public function syncIncomeTransactionsFromWorks($clientId = null, $date = null)
    {
        DB::beginTransaction();
        
        try {
            // Get all works with payments
            $query = Work::with(['parameters', 'client'])
                ->whereNotNull('paid_at')
                ->whereNotNull('client_id');
            
            if ($clientId) {
                $query->where('client_id', $clientId);
            }
            
            if ($date) {
                $query->whereDate('paid_at', $date);
            }
            
            $works = $query->get();
            
            // Group by client_id and payment date
            $groupedPayments = [];
            
            foreach ($works as $work) {
                $clientId = $work->client_id;
                $paidAt = $work->paid_at;
                
                if (!$clientId || !$paidAt) {
                    continue;
                }
                
                $paymentDate = Carbon::parse($paidAt)->format('Y-m-d');
                $key = $clientId . '_' . $paymentDate;
                
                if (!isset($groupedPayments[$key])) {
                    $groupedPayments[$key] = [
                        'client_id' => $clientId,
                        'date' => $paymentDate,
                        'total_amount' => 0,
                        'works' => [],
                        'created_by' => auth()->id() ?? $work->user_id ?? null,
                    ];
                }
                
                // Sum all payment amounts for this client on this date
                $paid = $work->getParameterValue(Work::PAID) ?? 0;
                $vatPaid = $work->getParameterValue(Work::VATPAYMENT) ?? 0;
                $illegalPaid = $work->getParameterValue(Work::ILLEGALPAID) ?? 0;
                
                $totalPayment = $paid + $vatPaid + $illegalPaid;
                
                if ($totalPayment > 0) {
                    $groupedPayments[$key]['total_amount'] += $totalPayment;
                    $groupedPayments[$key]['works'][] = $work->id;
                }
            }
            
            // Process VAT payments separately (grouped by VAT date)
            $vatQuery = Work::with(['parameters', 'client'])
                ->whereNotNull('vat_date')
                ->whereNotNull('client_id');
            
            if ($clientId) {
                $vatQuery->where('client_id', $clientId);
            }
            
            if ($date) {
                $vatQuery->whereDate('vat_date', $date);
            }
            
            $vatWorks = $vatQuery->get();
            
            foreach ($vatWorks as $work) {
                $clientId = $work->client_id;
                $vatDate = $work->vat_date;
                
                if (!$clientId || !$vatDate) {
                    continue;
                }
                
                $vatPaymentDate = Carbon::parse($vatDate)->format('Y-m-d');
                $vatKey = $clientId . '_vat_' . $vatPaymentDate;
                
                $vatPaid = $work->getParameterValue(Work::VATPAYMENT) ?? 0;
                
                if ($vatPaid > 0) {
                    // If VAT date is same as paid_at, merge into existing transaction
                    $paidAt = $work->paid_at ? Carbon::parse($work->paid_at)->format('Y-m-d') : null;
                    
                    if ($paidAt === $vatPaymentDate) {
                        // Merge into existing payment transaction
                        $mainKey = $clientId . '_' . $vatPaymentDate;
                        if (isset($groupedPayments[$mainKey])) {
                            // Already included in main payment, skip
                            continue;
                        }
                    }
                    
                    // Create separate VAT transaction if not merged
                    if (!isset($groupedPayments[$vatKey])) {
                        $groupedPayments[$vatKey] = [
                            'client_id' => $clientId,
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
            
            // Create or update transactions
            foreach ($groupedPayments as $key => $paymentData) {
                if ($paymentData['total_amount'] <= 0) {
                    continue;
                }
                
                // Find existing transaction for this client + date + source
                $existingTransaction = Transaction::where('client_id', $paymentData['client_id'])
                    ->where('transaction_date', $paymentData['date'])
                    ->where('source', 'works')
                    ->where('type', Transaction::INCOME)
                    ->first();
                
                if ($existingTransaction) {
                    // Update existing transaction
                    $existingTransaction->update([
                        'amount' => $paymentData['total_amount'],
                        'user_id' => $paymentData['created_by'],
                        'note' => 'Mədaxil - İşlərdən ödənişlər (İş ID-ləri: ' . implode(', ', array_unique($paymentData['works'])) . ')',
                    ]);
                } else {
                    // Create new transaction
                    Transaction::create([
                        'client_id' => $paymentData['client_id'],
                        'transaction_date' => $paymentData['date'],
                        'type' => Transaction::INCOME,
                        'amount' => $paymentData['total_amount'],
                        'currency' => 'AZN',
                        'source' => 'works',
                        'status' => Transaction::SUCCESSFUL,
                        'user_id' => $paymentData['created_by'],
                        'note' => 'Mədaxil - İşlərdən ödənişlər (İş ID-ləri: ' . implode(', ', array_unique($paymentData['works'])) . ')',
                    ]);
                }
            }
            
            // Remove transactions that should no longer exist (zero payments)
            if ($clientId && $date) {
                // Only clean up for specific client/date
                Transaction::where('client_id', $clientId)
                    ->where('transaction_date', $date)
                    ->where('source', 'works')
                    ->where('type', Transaction::INCOME)
                    ->whereNotIn('id', function($query) use ($groupedPayments) {
                        // Keep transactions that still have payments
                    })
                    ->delete();
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error syncing income transactions from works: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Recalculate transactions for a specific work after payment changes
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
        
        if ($work->paid_at) {
            $dates[] = Carbon::parse($work->paid_at)->format('Y-m-d');
        }
        
        if ($work->vat_date) {
            $vatDate = Carbon::parse($work->vat_date)->format('Y-m-d');
            if (!in_array($vatDate, $dates)) {
                $dates[] = $vatDate;
            }
        }
        
        // Recalculate for each date
        foreach ($dates as $date) {
            $this->syncIncomeTransactionsFromWorks($work->client_id, $date);
        }
    }
    
    /**
     * Remove transactions when work payment is deleted
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
        
        if ($work->paid_at) {
            $dates[] = Carbon::parse($work->paid_at)->format('Y-m-d');
        }
        
        if ($work->vat_date) {
            $vatDate = Carbon::parse($work->vat_date)->format('Y-m-d');
            if (!in_array($vatDate, $dates)) {
                $dates[] = $vatDate;
            }
        }
        
        // Recalculate (will remove if total becomes zero)
        foreach ($dates as $date) {
            $this->syncIncomeTransactionsFromWorks($work->client_id, $date);
        }
    }
}

