<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerEngagementRequest;
use App\Models\CustomerEngagement;
use App\Models\Partner;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

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
        $search = $request->get('search');
        $user = $request->get('user');

        return view('pages.customer-engagements.index')
            ->with([
                'customer_engagements' => CustomerEngagement::with('user', 'client', 'partner')
                    ->when($search, fn ($query) => $query
                        ->whereHas('user',fn($q) => $q->where('name', 'like', "%$search%"))
                        ->orWhereHas('client',fn($q) => $q->where('fullname', 'like', "%$search%"))
                        ->orWhereHas('partner',fn($q) => $q->where('name', 'like', "%$search%"))
                    )->when($user, fn ($q) => $q->where('user_id', $user))
                    ->latest('id')
                    ->paginate($limit),
                'users' => User::isActive()->get(['id', 'name', 'surname'])
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
                'partners' => Partner::get(['id', 'name'])
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
                'partners' => Partner::get(['id', 'name'])
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
                'partners' => Partner::get(['id', 'name'])
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

    public function getAmount(CustomerEngagement $customerEngagement)
    {
        $client = $customerEngagement->getAttribute('client_id');
        $works = Work::query()->where('client_id', $client)->whereMonth('paid_at', now()->subMonth())->get();
        if (isNull($works)){
            $sum_total_payment = 0;
        }
        foreach ($works as $work){
            /**
             * @var Work $work
             */
            $sum_payment = $work->getParameter($work::PAID) + $work->getParameter($work::ILLEGALPAID);
            $total_payment[] = $sum_payment;
            $sum_total_payment = array_sum($total_payment);
        }
        $customerEngagement->setAttribute('amount',$sum_total_payment)->save();

        return redirect()->back();
    }
}
