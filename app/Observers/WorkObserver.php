<?php

namespace App\Observers;

use App\Models\Work;
use App\Services\CacheService;

class WorkObserver
{
    /**
     * @var CacheService $cacheService
     */
    private CacheService $cacheService;

    /**
     * Create a new job instance.
     *
     * @param CacheService $cacheService
     * @return void
     */
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
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
            $originalStatus = $work->getOriginal('status');
            $newStatus = $work->getAttribute('status');

            $work->hours()->create(['status' => $newStatus, 'updated_at' => now()]);

            if ($originalStatus === Work::PLANNED && $newStatus !== Work::PLANNED) {
                $work->setAttribute('entry_date', now());
            }
        }

        if(!auth()->user()->hasPermission('canRedirect-work') && $work->isDirty('user_id')){
            $work->setAttribute('status', Work::STARTED);
        }
    }
}
