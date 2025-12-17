<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Work;
use Carbon\Carbon;

/**
 * Isolated service for creating income transactions from work payments.
 * 
 * IMPORTANT: This service ONLY handles income (type=Transaction::INCOME).
 * It NEVER touches or modifies expense transactions (type=Transaction::EXPENSE).
 * 
 * Income is created ONLY when payment parameters increase:
 * - PAID (35) increases
 * - VATPAYMENT (36) increases  
 * - ILLEGALPAID (37) increases
 */
class WorkIncomeService
{
    /**
     * Handle income creation when a payment parameter increases.
     * 
     * This method is called ONLY when:
     * - PAID, VATPAYMENT, or ILLEGALPAID parameter value increases
     * - The new value is greater than the old value
     * 
     * @param Work $work The work being updated
     * @param int $parameterId The parameter ID (35=PAID, 36=VATPAYMENT, 37=ILLEGALPAID)
     * @param float $oldValue The previous parameter value
     * @param float $newValue The new parameter value
     * @return void
     */
    public function handlePaymentIncrease(Work $work, int $parameterId, float $oldValue, float $newValue): void
    {
        // Only process payment parameters
        if (!in_array($parameterId, [Work::PAID, Work::VATPAYMENT, Work::ILLEGALPAID])) {
            return;
        }

        // Only process if value increased
        $increase = $newValue - $oldValue;
        if ($increase <= 0) {
            return;
        }

        // Work must have a client
        if (!$work->client_id) {
            return;
        }

        // Get payment date (prefer paid_at, fallback to vat_date, then today)
        $paymentDate = $work->paid_at 
            ? Carbon::parse($work->paid_at)->format('Y-m-d')
            : ($work->vat_date 
                ? Carbon::parse($work->vat_date)->format('Y-m-d')
                : now()->format('Y-m-d'));

        // Reload work with parameters to get current totals
        $work->load('parameters');
        
        // Calculate total paid amount for this client on this date
        $totalPaid = $this->calculateTotalPaidForClientDate($work->client_id, $paymentDate);

        // Create or update income transaction for this client+date
        $this->syncIncomeTransaction($work->client_id, $paymentDate, $totalPaid);
    }

    /**
     * Calculate total paid amount for a client on a specific date.
     * 
     * Sums PAID + VATPAYMENT + ILLEGALPAID from all works for the client on the date.
     * 
     * @param int $clientId
     * @param string $date Y-m-d format
     * @return float
     */
    private function calculateTotalPaidForClientDate(int $clientId, string $date): float
    {
        $works = Work::where('client_id', $clientId)
            ->where(function ($query) use ($date) {
                $query->whereDate('paid_at', $date)
                    ->orWhereDate('vat_date', $date);
            })
            ->with('parameters')
            ->get();

        $total = 0;

        foreach ($works as $work) {
            // Only count if payment date matches
            $workPaymentDate = $work->paid_at 
                ? Carbon::parse($work->paid_at)->format('Y-m-d')
                : ($work->vat_date 
                    ? Carbon::parse($work->vat_date)->format('Y-m-d')
                    : null);

            if ($workPaymentDate === $date) {
                $paid = $work->getParameterValue(Work::PAID) ?? 0;
                $vatPayment = $work->getParameterValue(Work::VATPAYMENT) ?? 0;
                $illegalPaid = $work->getParameterValue(Work::ILLEGALPAID) ?? 0;
                
                $total += $paid + $vatPayment + $illegalPaid;
            }
        }

        return $total;
    }

    /**
     * Create or update income transaction for a client+date combination.
     * 
     * IMPORTANT: Only creates/updates transactions with:
     * - type = Transaction::INCOME (1)
     * - source = 'works'
     * 
     * NEVER touches expense transactions.
     * 
     * @param int $clientId
     * @param string $date Y-m-d format
     * @param float $totalAmount
     * @return void
     */
    private function syncIncomeTransaction(int $clientId, string $date, float $totalAmount): void
    {
        // If total is zero, delete any existing income transaction for this client+date
        if ($totalAmount <= 0) {
            Transaction::where('type', Transaction::INCOME)
                ->where('source', 'works')
                ->where('client_id', $clientId)
                ->whereDate('transaction_date', $date)
                ->delete();
            return;
        }

        // Find or create income transaction for this client+date
        $transaction = Transaction::where('type', Transaction::INCOME)
            ->where('source', 'works')
            ->where('client_id', $clientId)
            ->whereDate('transaction_date', $date)
            ->first();

        if ($transaction) {
            // Update existing income transaction
            $transaction->update([
                'amount' => $totalAmount,
                'currency' => 'AZN',
                'status' => Transaction::SUCCESSFUL,
            ]);
        } else {
            // Create new income transaction
            Transaction::create([
                'type' => Transaction::INCOME,
                'source' => 'works',
                'client_id' => $clientId,
                'transaction_date' => $date,
                'amount' => $totalAmount,
                'currency' => 'AZN',
                'status' => Transaction::SUCCESSFUL,
                'user_id' => auth()->id(),
                'note' => 'Mədaxil - İş ödənişləri',
            ]);
        }
    }

    /**
     * Remove income transaction when payments are cleared.
     * 
     * Called when payment parameters are set to 0 or payment dates are cleared.
     * 
     * @param Work $work
     * @return void
     */
    public function removeIncomeForWork(Work $work): void
    {
        if (!$work->client_id) {
            return;
        }

        // Get payment dates
        $dates = [];
        if ($work->paid_at) {
            $dates[] = Carbon::parse($work->paid_at)->format('Y-m-d');
        }
        if ($work->vat_date && !in_array(Carbon::parse($work->vat_date)->format('Y-m-d'), $dates)) {
            $dates[] = Carbon::parse($work->vat_date)->format('Y-m-d');
        }

        // Recalculate total for each date and sync
        foreach ($dates as $date) {
            $totalPaid = $this->calculateTotalPaidForClientDate($work->client_id, $date);
            $this->syncIncomeTransaction($work->client_id, $date, $totalPaid);
        }
    }
}

