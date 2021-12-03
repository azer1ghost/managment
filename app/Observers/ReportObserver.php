<?php

namespace App\Observers;

use App\Models\Report;

class ReportObserver
{
    public function deleted(Report $report)
    {
        $report->reports()->delete();
    }
}
