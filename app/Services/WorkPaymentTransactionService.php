<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WorkPaymentTransactionService
{
    /**
     * Sync income transaction for a work's payment
     * Simple: ONE client + ONE date = ONE income transaction
     * 
     * @param Work $work
     * @return void
     */
    public function syncIncomeForWork(Work $work)
    {
        if (!$work->client_id) {
            return;
        }

        // Get ONLY paid values (not amounts due)
        $paid = (float)($work->getParameterValue(Work::PAID) ?? 0);
        $vatPaid = (float)($work->getParameterValue(Work::VATPAYMENT) ?? 0);
        $illegalPaid = (float)($work->getParameterValue(Work::ILLEGALPAID) ?? 0);
        
        $totalPaid = $paid + $vatPaid + $illegalPaid;
        
        // Get payment date (use paid_at, fallback to vat_date)
        $paymentDate = null;
        if ($work->paid_at) {
            $paymentDate = Carbon::parse($work->paid_at)->format('Y-m-d');
        } elseif ($work->vat_date) {
            $paymentDate = Carbon::parse($work->vat_date)->format('Y-m-d');
        }
        
        if (!$paymentDate) {
            // No payment date = no income transaction
            return;
        }
        
        DB::beginTransaction();
        
        try {
            // Get ALL works for this client on this date
            $worksOnDate = Work::with('parameters')
                ->where('client_id', $work->client_id)
                ->where(function($q) use ($paymentDate) {
                    $q->whereDate('paid_at', $paymentDate)
                      ->orWhereDate('vat_date', $paymentDate);
                })
                ->get();
            
            // Sum ALL paid values for this client on this date
            $totalAmount = 0;
            $workIds = [];
            
            foreach ($worksOnDate as $w) {
                $wPaid = (float)($w->getParameterValue(Work::PAID) ?? 0);
                $wVatPaid = (float)($w->getParameterValue(Work::VATPAYMENT) ?? 0);
                $wIllegalPaid = (float)($w->getParameterValue(Work::ILLEGALPAID) ?? 0);
                
                $wTotal = $wPaid + $wVatPaid + $wIllegalPaid;
                
                if ($wTotal > 0) {
                    $totalAmount += $wTotal;
                    $workIds[] = $w->id;
                }
            }
            
            // Find existing income transaction for this client + date
            // CRITICAL: Only look for income from works - NEVER touch expenses
            $existingTransaction = Transaction::where('client_id', $work->client_id)
                ->where(function($q) use ($paymentDate) {
                    $q->where('transaction_date', $paymentDate)
                      ->orWhere(function($subQ) use ($paymentDate) {
                          $subQ->whereNull('transaction_date')
                               ->whereDate('created_at', $paymentDate);
                      });
                })
                ->where('source', 'works')
                ->where('type', Transaction::INCOME)
                ->first();
            
            if ($totalAmount > 0) {
                if ($existingTransaction) {
                    // Update existing income transaction
                    $existingTransaction->update([
                        'amount' => $totalAmount,
                        'user_id' => auth()->id() ?? $work->user_id ?? null,
                        'note' => 'Mədaxil - İşlərdən ödənişlər',
                    ]);
                } else {
                    // Create new income transaction
                    Transaction::create([
                        'client_id' => $work->client_id,
                        'transaction_date' => $paymentDate,
                        'type' => Transaction::INCOME,
                        'amount' => $totalAmount,
                        'currency' => 'AZN',
                        'source' => 'works',
                        'status' => Transaction::SUCCESSFUL,
                        'user_id' => auth()->id() ?? $work->user_id ?? null,
                        'note' => 'Mədaxil - İşlərdən ödənişlər',
                    ]);
                }
            } else {
                // No payments = delete income transaction if exists
                if ($existingTransaction) {
                    $existingTransaction->delete();
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error syncing income transaction: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove income transaction when payment is deleted
     * 
     * @param Work $work
     * @return void
     */
    public function removeIncomeForWork(Work $work)
    {
        if (!$work->client_id) {
            return;
        }
        
        // Get dates before deletion
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
        
        // Recalculate for each date (will delete if no payments)
        foreach ($dates as $date) {
            // Get a work with this date to recalculate
            $workOnDate = Work::where('client_id', $work->client_id)
                ->where(function($q) use ($date) {
                    $q->whereDate('paid_at', $date)
                      ->orWhereDate('vat_date', $date);
                })
                ->with('parameters')
                ->first();
            
            if ($workOnDate) {
                $this->syncIncomeForWork($workOnDate);
            } else {
                // No works on this date = delete transaction
                Transaction::where('client_id', $work->client_id)
                    ->where(function($q) use ($date) {
                        $q->where('transaction_date', $date)
                          ->orWhere(function($subQ) use ($date) {
                              $subQ->whereNull('transaction_date')
                                   ->whereDate('created_at', $date);
                          });
                    })
                    ->where('source', 'works')
                    ->where('type', Transaction::INCOME)
                    ->delete();
            }
        }
    }
}
