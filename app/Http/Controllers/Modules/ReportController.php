<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\Advertising;
use App\Models\DailyReport;
use App\Models\Report;
use App\Models\User;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Report::class);
    }

    // Custom policies
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
                ->paginate(10)
        ]);
    }

    public function showSubReports(Report $report)
    {
        return view('panel.pages.reports.sub_reports')->with([
            'parent' => $report,
            'subReports' => $report->reports()->latest()->paginate(10)
        ]);
    }

    public function generateReports()
    {
        foreach (User::isActive()->get() as $user){
            $user_id = $user->getAttribute('id');

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

    public function createSubReport(Report $report)
    {
        return view('panel.pages.reports.edit')->with([
            'method' => 'POST',
            'data' => new DailyReport(),
            'action' => route('reports.sub.generate', $report),
            'parent' => $report
        ]);
    }

    public function showSubReport(DailyReport $report)
    {
        return view('panel.pages.reports.edit')->with([
            'method' => null,
            'data' => $report,
            'action' => null,
            'parent' => $report->getRelationValue('parent')
        ]);
    }

    public function editSubReport(DailyReport $report)
    {
        return view('panel.pages.reports.edit')->with([
            'method' => 'PUT',
            'data' => $report,
            'action' => route('reports.sub.update', $report),
            'parent' => $report->getRelationValue('parent')
        ]);
    }

    public function generateSubReport(ReportRequest $request, Report $report)
    {
        $report->reports()->create($request->validated());

        return redirect()->route('reports.subs.show', $report);
    }

    public function updateSubReport(ReportRequest $request, DailyReport $report)
    {
        $report->update($request->validated());

        return redirect()->route('reports.subs.show', $report->getAttribute('report_id'));
    }

    public function destroy(Report $report)
    {
        if ($report->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
