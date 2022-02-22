<?php

namespace App\Observers;

use App\Events\TaskCreated;
use App\Models\Inquiry;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Services\CacheService;

class UserObserver
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
    public function updating(User $user)
    {
        if ($user->isDirty('disabled_at')) {
            unset($this->cacheService->getData('statistics')['users']);
        }
    }
}
