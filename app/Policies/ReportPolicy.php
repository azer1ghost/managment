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
        return $user->getAttribute('id') == $report->getAttribute('chief_id');
    }

    public function generateSubReport(User $user, Report $report): bool
    {
        return $user->getRelationValue('userReport')->getAttribute('id') == $report->getAttribute('id');
    }

    public function showSubReport(User $user, DailyReport $report): bool
    {
        return $user->getAttribute('id') == $report->getRelationValue('parent')->getAttribute('chief_id');
    }

    public function updateSubReport(User $user, DailyReport $report): bool
    {
        return
            $user->getAttribute('id') == $report->getRelationValue('parent')->getAttribute('chief_id') &&
            $report->getAttribute('created_at')->format('Y-m-d') == now()->format('Y-m-d');
    }

    public function delete(User $user, Report $report): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }
}