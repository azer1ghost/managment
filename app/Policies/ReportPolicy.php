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

    public function showSubReports(User $user, Report $report): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $this->generateReports($user) ||
            $user->getAttribute('id') == $report->getAttribute('chief_id');
    }

    public function generateSubReport(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function showSubReport(User $user, DailyReport $report): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $this->generateReports($user) ||
            $user->getAttribute('id') == $report->getRelationValue('parent')->getAttribute('chief_id');
    }

    public function updateSubReport(User $user, DailyReport $report): bool
    {
        return
            ($this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $user->getAttribute('id') == $report->getRelationValue('parent')->getAttribute('chief_id')) &&
            $report->getAttribute('date') == now()->format('Y-m-d');
    }

    public function delete(User $user, Report $report): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }
}