<?php

namespace App\Policies;

use App\Models\DailyReport;
use App\Models\Report;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyReportPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function showSubReport(User $user, DailyReport $report): bool
    {
        return
            $this->canManage($user, 'report', __FUNCTION__) ||
            $user->getAttribute('id') == $report->getRelationValue('parent')->getAttribute('chief_id');
    }

    public function updateSubReport(User $user, DailyReport $report): bool
    {
        return
            ($this->canManage($user, 'report', __FUNCTION__) ||
                $user->getAttribute('id') == $report->getRelationValue('parent')->getAttribute('chief_id')) &&
            $report->getAttribute('created_at')->format('Y-m-d') == now()->format('Y-m-d');
    }
}