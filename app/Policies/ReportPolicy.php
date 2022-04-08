<?php

namespace App\Policies;

use App\Models\DailyReport;
use App\Models\Report;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function generateReports(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function generateSubReport(User $user, Report $report): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) &&
            $user->getAttribute('id') == $report->getAttribute('chief_id');
    }

    public function showSubReports(User $user, Report $report): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $user->getAttribute('id') == $report->getAttribute('chief_id');
    }

    public function delete(User $user, Report $report): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }
}