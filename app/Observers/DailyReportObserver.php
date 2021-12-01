<?php

namespace App\Observers;

use App\Models\DailyReport;

class DailyReportObserver
{
    public function creating(DailyReport $report)
    {
        if($report->isClean('date')){
            $report->setAttribute('date', now());
        }
    }
}
