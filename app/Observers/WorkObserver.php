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
    }
    
    public function updated(Work $work)
    {
        // Sync income transaction when payment fields change
        // Simple: if paid_at or vat_date changed, recalculate income
        if ($work->wasChanged(['paid_at', 'vat_date', 'client_id'])) {
            $work->load('parameters');
            $this->transactionService->syncIncomeForWork($work);
        }
        
        // If payment was removed, recalculate
        if (($work->wasChanged('paid_at') && $work->paid_at === null) ||
            ($work->wasChanged('vat_date') && $work->vat_date === null)) {
            $this->transactionService->removeIncomeForWork($work);
        }
    }
    
    public function deleted(Work $work)
    {
        // Remove income transaction when work is deleted
        if ($work->client_id) {
            $this->transactionService->removeIncomeForWork($work);
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
