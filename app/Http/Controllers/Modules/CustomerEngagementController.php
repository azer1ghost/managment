<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerEngagementRequest;
use App\Models\Company;
use App\Models\CustomerEngagement;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerEngagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(CustomerEngagement::class, 'customer_engagement');
    }
    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $company = $request->get('company');

        return view('panel.pages.customer-engagements.index')
            ->with([
                'customer_engagements' => CustomerEngagement::query()
                    ->when($company, fn($query) => $query->where('company_id', $company))
                    ->latest('id')
                    ->paginate($limit),
                'companies' => Company::get(['id', 'name']),
            ]);
    }


    public function create()
    {
        return view('panel.pages.customer-engagements.edit')
            ->with([
                'action' => route('customer-engagement.store'),
                'method' => 'POST',
                'data' => new CustomerEngagement(),
                'companies' => Company::get(['id', 'name']),
                'users' => User::get(['id', 'name', 'surname', 'position_id', 'role_id']),
            ]);
    }

    public function store(CustomerEngagementRequest $request): RedirectResponse
    {
        $customerEngagement = CustomerEngagement::create($request->validated());

        return redirect()
            ->route('customer-engagement.index')
            ->withNotify('success', $customerEngagement->getAttribute('name'));
    }

    public function show(CustomerEngagement $customerEngagement)
    {
        return view('panel.pages.customer-engagements.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $customerEngagement,
                'companies' => Company::get(['id', 'name']),
                'users' => User::oldest('name')->get(['id', 'name', 'surname', 'position_id', 'role_id']),
            ]);
    }

    public function edit(CustomerEngagement $customerEngagement)
    {
        return view('panel.pages.customer-engagements.edit')
            ->with([
                'action' => route('customer-engagement.update', $customerEngagement),
                'method' => "PUT",
                'data' => $customerEngagement,
                'companies' => Company::get(['id', 'name']),
                'users' => User::get(['id', 'name', 'surname', 'position_id', 'role_id']),
            ]);
    }

    public function update(CustomerEngagementRequest $request, CustomerEngagement $customerEngagement): RedirectResponse
    {
        $customerEngagement->update($request->validated());

        return back()->withNotify('info', $customerEngagement->getAttribute('name'));
    }

    public function destroy(CustomerEngagement $customerEngagement)
    {
        if ($customerEngagement->delete()) {

            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
