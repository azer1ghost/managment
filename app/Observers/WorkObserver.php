<?php

namespace App\Observers;

use App\Models\Work;

class WorkObserver
{
    public function creating(Work $work)
    {
        if($work->getAttribute('status') == $work::STARTED){
            $work->setAttribute('started_at', now());
        }
        if($work->getAttribute('status') == $work::DONE){
            $work->setAttribute('done_at', now());
        }
        $work->setAttribute('code', $work::generateCustomCode());
    }

    public function updating(Work $work)
    {
        if($work->getAttribute('status') == $work::STARTED){
            $work->setAttribute('started_at', now());
        }
        if($work->getAttribute('status') == $work::DONE){
            $work->setAttribute('done_at', now());
        }
    }
}
