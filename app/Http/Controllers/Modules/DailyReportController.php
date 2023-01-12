<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Advertising;
use App\Models\DailyReport;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;

class DailyReportController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(DailyReport::class, 'report');
//    }

    /* Custom policies to extend default resource policies */
    protected function resourceAbilityMap()
    {
        return [
            'showSubReport' => 'showSubReport',
            'editSubReport' => 'updateSubReport',
            'updateSubReport' => 'updateSubReport',
        ];
    }

    /* show specific daily report for the given chief */
    public function showSubReport(DailyReport $report)
    {
        return view('pages.reports.edit')->with([
            'method' => null,
            'data' => $report,
            'action' => null,
            'parent' => $report->getRelationValue('parent')
        ]);
    }

    /* edit specific daily report for the given chief */
    public function editSubReport(DailyReport $report)
    {
        return view('pages.reports.edit')->with([
            'method' => 'PUT',
            'data' => $report,
            'action' => route('reports.sub.update', $report),
            'parent' => $report->getRelationValue('parent')
        ]);
    }

    /* update specific daily report for the given chief */
    public function updateSubReport(ReportRequest $request, DailyReport $report)
    {
        $report->update($request->validated());

        return redirect()->route('reports.subs.show', $report->getAttribute('report_id'));
    }
}
