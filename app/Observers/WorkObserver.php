<?php

namespace App\Observers;

use App\Models\Work;
use App\Services\CacheService;
use App\Services\WorkPaymentTransactionService;

class WorkObserver
{
    /**
     * @var CacheService $cacheService
     */
    private CacheService $cacheService;
    
    /**
     * @var WorkPaymentTransactionService $transactionService
     */
    private WorkPaymentTransactionService $transactionService;

    /**
     * Create a new job instance.
     *
     * @param CacheService $cacheService
     * @param WorkPaymentTransactionService $transactionService
     * @return void
     */
    public function __construct(CacheService $cacheService, WorkPaymentTransactionService $transactionService)
    {
        $this->cacheService = $cacheService;
        $this->transactionService = $transactionService;
    }

    public function creating(Work $work)
    {
        $work->setAttribute('status', $work::PENDING);
    }

    public function created(Work $work)
    {
        $work->hours()->create(['status' => $work::PENDING, 'updated_at' => now()]);
    }

    public function updating(Work $work)
    {
        if($work->isDirty('status')){
            $work->hours()->create(['status' => $work->getAttribute('status'), 'updated_at' => now()]);
        }

        if(!auth()->user()->hasPermission('canRedirect-work') && $work->isDirty('user_id')){
            $work->setAttribute('status', Work::STARTED);
        }
        
        // Sync income transactions when payment-related fields change
        // ONLY for paid values (PAID, VATPAYMENT, ILLEGALPAID) - NOT for expense transactions
        if ($work->isDirty(['paid_at', 'vat_date']) || 
            $work->isDirty('client_id')) {
            // Recalculate ONLY income transactions after update
            // Expenses are NEVER touched
            $work->load('parameters');
            $this->transactionService->recalculateWorkTransactions($work);
        }
    }
    
    public function updated(Work $work)
    {
        // Handle payment deletion (when paid_at or vat_date is set to null)
        if ($work->wasChanged('paid_at') && $work->paid_at === null) {
            $this->transactionService->removeWorkTransactions($work);
        }
        if ($work->wasChanged('vat_date') && $work->vat_date === null) {
            $this->transactionService->removeWorkTransactions($work);
        }
    }
    
    public function deleted(Work $work)
    {
        // Remove transactions when work is deleted
        if ($work->client_id) {
            $this->transactionService->removeWorkTransactions($work);
        }
    }
    
    /**
     * Check if payment parameters have changed
     */
    private function isPaymentParameterChanged(Work $work): bool
    {
        // This is a simplified check - in reality, we'd need to track pivot changes
        // For now, we'll rely on paid_at and vat_date changes
        return false;
    }
}
