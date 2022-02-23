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
        $work->setAttribute('code', $work::generateCustomCode());
    }

    public function created(Work $work)
    {
        $work->hours()->create(['status' => $work::PENDING, 'updated_at' => now()]);
    }

    public function updating(Work $work)
    {
        if($work->isDirty('status')){
            $work->hours()->create(['status' => $work->getAttribute('status'), 'updated_at' => now()]);

            if($work->isClean('datetime') && $work->getAttribute('status') == $work::DONE){
                $work->setAttribute('datetime', now());
            }
        }

        if(!auth()->user()->hasPermission('canRedirect-work') && $work->isDirty('user_id')){
            $work->setAttribute('status', Work::STARTED);
        }
    }
}
