<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Advertising;
use App\Models\DailyReport;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Report::class);
    }

    /* Custom policies to extend default resource policies */
    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewAny',
            'generateReports' => 'generateReports',
            'showSubReports' => 'showSubReports',
            'createSubReport' => 'generateSubReport',
            'generateSubReport' => 'generateSubReport',
            'showSubReport' => 'showSubReport',
            'editSubReport' => 'updateSubReport',
            'updateSubReport' => 'updateSubReport',
            'destroy' => 'delete',
        ];
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'generateReports'];
    }

    public function index()
    {
        return view('panel.pages.reports.index')->with([
            'reports' => Report::withCount('reports')
                ->when(Report::cannotViewAll(), fn($query) => $query->where('chief_id', auth()->id()))
                ->oldest('updated_at')
                ->simplePaginate(10)
        ]);
    }

    /*  show all reports of a given chief */
    public function showSubReports(Report $report)
    {
        return view('panel.pages.reports.sub_reports')->with([
            'parent' => $report,
            'data' => $report->reports()->latest()->get()->groupBy(function($d) {
                return Carbon::parse($d->date)->format('M Y');
            }),
        ]);
    }

    /*  generate list of chiefs who should give a report */
    public function generateReports()
    {
        foreach (User::isActive()->get() as $user){
            $user_id = $user->getAttribute('id');

            /* check if the chief already exists in reports table */
            if(
                ($user->isDeveloper() && $user_id == User::CHIEF_DEVELOPER ||
                $user->isNotDeveloper() && $user->hasPermission('department-chief'))
                && Report::where('chief_id', $user_id)->first() == null
            ){
                Report::create(['chief_id' => $user_id]);
            }
        }
        return back();
    }

    /* view of a daily report creation for the given chief */
    public function createSubReport(Report $report)
    {
        return view('panel.pages.reports.edit')->with([
            'method' => 'POST',
            'data' => new DailyReport(),
            'action' => route('reports.sub.generate', $report),
            'parent' => $report
        ]);
    }

    /* generate daily report for the given chief */
    public function generateSubReport(ReportRequest $request, Report $report)
    {
        $report->reports()->create($request->validated());

        return redirect()->route('reports.subs.show', $report);
    }

    /* show specific daily report for the given chief */
    public function showSubReport(DailyReport $report)
    {
        return view('panel.pages.reports.edit')->with([
            'method' => null,
            'data' => $report,
            'action' => null,
            'parent' => $report->getRelationValue('parent')
        ]);
    }

    /* edit specific daily report for the given chief */
    public function editSubReport(DailyReport $report)
    {
        return view('panel.pages.reports.edit')->with([
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

    /* delete given chief from reports table */
    public function destroy(Report $report)
    {
        if ($report->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}