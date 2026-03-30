<?php

namespace App\Http\Controllers\Modules;

use App\Exports\DebitorsExport;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebitorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $filters = [
            'invoice_company_id' => $request->get('invoice_company_id'),
            'client_id'          => $request->get('client_id'),
            'debitor_status'     => $request->get('debitor_status'),
            'invoiced_date_from' => $request->get('invoiced_date_from'),
            'invoiced_date_to'   => $request->get('invoiced_date_to'),
        ];

        $limit = $request->get('limit', 25);

        $query = Work::query()
            ->whereNotNull('invoiced_date')
            ->with([
                'invoiceCompany:id,name',
                'client:id,fullname,voen',
                'parameters',
            ]);

        if (!empty($filters['invoice_company_id'])) {
            $query->where('invoice_company_id', $filters['invoice_company_id']);
        }

        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        if (!empty($filters['invoiced_date_from']) && !empty($filters['invoiced_date_to'])) {
            $query->whereBetween('invoiced_date', [
                $filters['invoiced_date_from'],
                $filters['invoiced_date_to'],
            ]);
        }

        $works = $query->orderByDesc('invoiced_date')->paginate($limit);

        // Status filter is applied in PHP after parameter loading
        if (!empty($filters['debitor_status'])) {
            $statusFilter = $filters['debitor_status'];
            $works->setCollection(
                $works->getCollection()->filter(function ($work) use ($statusFilter) {
                    return $this->getVeziyyet($work) === $statusFilter;
                })->values()
            );
        }

        $companies = Company::get(['id', 'name']);
        $clients   = Client::whereHas('works', fn($q) => $q->whereNotNull('invoiced_date'))
            ->get(['id', 'fullname', 'voen']);

        return view('pages.debitors.index', compact('works', 'filters', 'companies', 'clients', 'limit'));
    }

    public function export(Request $request)
    {
        $filters = [
            'invoice_company_id' => $request->get('invoice_company_id'),
            'client_id'          => $request->get('client_id'),
            'debitor_status'     => $request->get('debitor_status'),
            'invoiced_date_from' => $request->get('invoiced_date_from'),
            'invoiced_date_to'   => $request->get('invoiced_date_to'),
        ];

        return (new DebitorsExport($filters))->download('debitors.xlsx');
    }

    protected function getVeziyyet(Work $work): string
    {
        $amount     = (float) ($work->getParameter(Work::AMOUNT) ?? 0);
        $vat        = (float) ($work->getParameter(Work::VAT) ?? 0);
        $paid       = (float) ($work->getParameter(Work::PAID) ?? 0);
        $vatPayment = (float) ($work->getParameter(Work::VATPAYMENT) ?? 0);

        $totalPaid = $paid + $vatPayment;

        if ($totalPaid <= 0) {
            return 'Açıq';
        }

        if (abs($amount - $paid) < 0.01 && abs($vat - $vatPayment) < 0.01) {
            return 'Bağlı';
        }

        return 'Qismən';
    }
}
