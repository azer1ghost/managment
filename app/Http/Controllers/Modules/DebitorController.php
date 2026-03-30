<?php

namespace App\Http\Controllers\Modules;

use App\Exports\DebitorsExport;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
            'payment_method'     => $request->get('payment_method'),
            'invoiced_date_from' => $request->get('invoiced_date_from'),
            'invoiced_date_to'   => $request->get('invoiced_date_to'),
        ];

        $limit = (int) $request->get('limit', 25);

        $rows = $this->buildRows($filters);

        // Status filter in PHP (after aggregation)
        if (!empty($filters['debitor_status'])) {
            $rows = $rows->filter(fn($r) => $r->veziyyet === $filters['debitor_status'])->values();
        }

        // Grand totals (before pagination)
        $totals = [
            'amount'      => $rows->sum('amount'),
            'vat'         => $rows->sum('vat'),
            'paid'        => $rows->sum('paid'),
            'vat_payment' => $rows->sum('vat_payment'),
            'qaliq'       => $rows->sum('qaliq'),
            'qaliq_edv'   => $rows->sum('qaliq_edv'),
        ];

        // Manual pagination
        $page  = (int) $request->get('page', 1);
        $works = new LengthAwarePaginator(
            $rows->forPage($page, $limit),
            $rows->count(),
            $limit,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $companies      = Company::get(['id', 'name']);
        $clients        = Client::whereHas('works', fn($q) => $q->whereNotNull('invoiced_date'))
            ->get(['id', 'fullname', 'voen']);
        $paymentMethods = trans('translates.payment_methods');

        return view('pages.debitors.index',
            compact('works', 'filters', 'companies', 'clients', 'limit', 'totals', 'paymentMethods'));
    }

    public function export(Request $request)
    {
        $filters = [
            'invoice_company_id' => $request->get('invoice_company_id'),
            'client_id'          => $request->get('client_id'),
            'debitor_status'     => $request->get('debitor_status'),
            'payment_method'     => $request->get('payment_method'),
            'invoiced_date_from' => $request->get('invoiced_date_from'),
            'invoiced_date_to'   => $request->get('invoiced_date_to'),
        ];

        return (new DebitorsExport($filters))->download('debitors.xlsx');
    }

    // -------------------------------------------------------------------------

    /**
     * Build aggregated rows grouped by invoice code + client + company.
     * Works without a code are treated as individual invoice rows.
     */
    public function buildRows(array $filters): \Illuminate\Support\Collection
    {
        $query = DB::table('works as w')
            ->selectRaw("
                COALESCE(w.code, CONCAT('_id_', w.id))  AS invoice_key,
                w.code,
                w.client_id,
                w.invoice_company_id,
                w.payment_method,
                MIN(w.invoiced_date)                     AS invoiced_date,
                MIN(w.paid_at)                           AS paid_at,
                c.fullname                               AS client_name,
                c.voen,
                ic.name                                  AS invoice_company_name,
                SUM(CASE WHEN wp.parameter_id = ? THEN CAST(wp.value AS DECIMAL(15,4)) ELSE 0 END) AS amount,
                SUM(CASE WHEN wp.parameter_id = ? THEN CAST(wp.value AS DECIMAL(15,4)) ELSE 0 END) AS vat,
                SUM(CASE WHEN wp.parameter_id = ? THEN CAST(wp.value AS DECIMAL(15,4)) ELSE 0 END) AS paid,
                SUM(CASE WHEN wp.parameter_id = ? THEN CAST(wp.value AS DECIMAL(15,4)) ELSE 0 END) AS vat_payment
            ", [Work::AMOUNT, Work::VAT, Work::PAID, Work::VATPAYMENT])
            ->leftJoin('work_parameter as wp', 'w.id', '=', 'wp.work_id')
            ->leftJoin('clients as c', 'w.client_id', '=', 'c.id')
            ->leftJoin('companies as ic', 'w.invoice_company_id', '=', 'ic.id')
            ->whereNull('w.deleted_at')
            ->whereNotNull('w.invoiced_date')
            ->groupByRaw("COALESCE(w.code, CONCAT('_id_', w.id)), w.code, w.client_id, w.invoice_company_id, w.payment_method, c.fullname, c.voen, ic.name")
            ->orderByRaw('MIN(w.invoiced_date) DESC');

        if (!empty($filters['invoice_company_id'])) {
            $query->where('w.invoice_company_id', $filters['invoice_company_id']);
        }
        if (!empty($filters['client_id'])) {
            $query->where('w.client_id', $filters['client_id']);
        }
        if (!empty($filters['payment_method'])) {
            $query->where('w.payment_method', $filters['payment_method']);
        }
        if (!empty($filters['invoiced_date_from'])) {
            $query->where('w.invoiced_date', '>=', $filters['invoiced_date_from']);
        }
        if (!empty($filters['invoiced_date_to'])) {
            $query->where('w.invoiced_date', '<=', $filters['invoiced_date_to']);
        }

        return $query->get()->map(function ($row) {
            $row->amount      = (float) $row->amount;
            $row->vat         = (float) $row->vat;
            $row->paid        = (float) $row->paid;
            $row->vat_payment = (float) $row->vat_payment;
            $row->qaliq       = $row->amount - $row->paid;
            $row->qaliq_edv   = $row->vat - $row->vat_payment;

            $totalPaid = $row->paid + $row->vat_payment;

            if ($totalPaid <= 0) {
                $row->veziyyet = 'Açıq';
            } elseif (abs($row->amount - $row->paid) < 0.01 && abs($row->vat - $row->vat_payment) < 0.01) {
                $row->veziyyet = 'Bağlı';
            } else {
                $row->veziyyet = 'Qismən';
            }

            return $row;
        });
    }
}
