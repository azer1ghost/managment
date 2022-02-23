<?php

namespace App\Observers;

use App\Events\TaskCreated;
use App\Models\Inquiry;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Services\CacheService;

class InquiryObserver
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

    /**
     * @return void
     */
    public function updating(Inquiry $inquiry)
    {
        //
    }
}
