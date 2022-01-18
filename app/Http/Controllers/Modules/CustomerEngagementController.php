<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerEngagementRequest;
use App\Models\Client;
use App\Models\CustomerEngagement;
use App\Models\User;
use App\Models\Work;
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

        return view('pages.customer-engagements.index')
            ->with([
                'customer_engagements' => CustomerEngagement::with('user', 'client')
                    ->latest('id')
                    ->paginate($limit),
            ]);
    }


    public function create()
    {
        return view('pages.customer-engagements.edit')
            ->with([
                'action' => route('customer-engagement.store'),
                'method' => 'POST',
                'data' => new CustomerEngagement(),
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
        return view('pages.customer-engagements.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $customerEngagement,
                'users' => User::oldest('name')->get(['id', 'name', 'surname', 'position_id', 'role_id']),
            ]);
    }

    public function edit(CustomerEngagement $customerEngagement)
    {
        return view('pages.customer-engagements.edit')
            ->with([
                'action' => route('customer-engagement.update', $customerEngagement),
                'method' => "PUT",
                'data' => $customerEngagement,
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
