@extends('pages.transit.layout')

@section('title', 'Online Transit | Account')

@section('content')
<div class="row gutters-sm">
    <div class="col-md-4 mb-4">
        <div class="transit-card">
            <div class="card-body text-center">
                <div class="d-flex flex-column align-items-center">
                    <div class="profile-avatar mb-3">
                        <img src="{{asset('assets/images/diamond-green.png')}}" alt="Profile" 
                             class="rounded-circle" width="150" height="150" style="object-fit: cover;">
                    </div>
                    <div class="mt-3">
                        <h4 class="mb-1">{{auth()->user()->getFullnameAttribute()}}</h4>
                        <p class="text-secondary mb-1">
                            <i class="fas fa-id-card"></i> {{auth()->user()->getAttribute('voen') ?: 'N/A'}}
                        </p>
                        <p class="text-muted mb-3">
                            <i class="fas fa-envelope"></i> {{auth()->user()->getAttribute('email')}}
                        </p>
                        <div class="balance-card p-3 rounded mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <small class="d-block mb-1">Current Balance</small>
                            <h3 class="mb-0">{{number_format(auth()->user()->getAttribute('balance') ?? 0, 2)}} AZN</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="transit-card mt-3">
            <div class="card-body p-0">
                <nav class="nav flex-column nav-pills" id="profileTabs" role="tablist">
                    <a class="nav-link active" id="tab-account" data-toggle="tab" href="#pills-account" role="tab">
                        <i class="fas fa-user"></i> Account
                    </a>
                    <a class="nav-link" id="tab-balance" data-toggle="tab" href="#pills-balance" role="tab">
                        <i class="fas fa-wallet"></i> Balance
                    </a>
                    <a class="nav-link" id="tab-order" data-toggle="tab" href="#pills-order" role="tab">
                        <i class="fas fa-shopping-cart"></i> My Orders
                    </a>
                    <a class="nav-link" id="tab-transactions" data-toggle="tab" href="#pills-transactions" role="tab">
                        <i class="fas fa-exchange-alt"></i> Transactions
                    </a>
                    <a class="nav-link" href="{{ route('service') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="transit-card">
            <div class="card-body">
                <div class="tab-content">
                    <!-- Account Tab -->
                    <div class="tab-pane fade show active" id="pills-account" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><i class="fas fa-user text-primary"></i> Account Information</h4>
                            <a href="{{route('profile.edit', auth()->id())}}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                        </div>

                        <div class="info-section">
                            <div class="info-item mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-circle text-primary me-2 fa-lg"></i>
                                    <h6 class="mb-0 text-muted">Full Name</h6>
                                </div>
                                <p class="mb-0 fs-5">{{auth()->user()->getFullnameAttribute()}}</p>
                            </div>

                            <div class="info-item mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope text-primary me-2 fa-lg"></i>
                                    <h6 class="mb-0 text-muted">Email</h6>
                                </div>
                                <p class="mb-0 fs-5">{{auth()->user()->getAttribute('email')}}</p>
                            </div>

                            <div class="info-item mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-id-card text-primary me-2 fa-lg"></i>
                                    <h6 class="mb-0 text-muted">VOEN</h6>
                                </div>
                                <p class="mb-0 fs-5">{{auth()->user()->getAttribute('voen') ?: 'Not provided'}}</p>
                            </div>

                            <div class="info-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-phone text-primary me-2 fa-lg"></i>
                                    <h6 class="mb-0 text-muted">Phone Number</h6>
                                </div>
                                <p class="mb-0 fs-5">{{auth()->user()->getAttribute('phone') ?: 'Not provided'}}</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <a href="{{route('profile.edit', auth()->id())}}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                            <a href="{{ route('logout') }}" class="btn btn-danger" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>

                    <!-- Balance Tab -->
                    <div class="tab-pane fade" id="pills-balance" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><i class="fas fa-wallet text-primary"></i> Balance</h4>
                        </div>

                        <div class="balance-display text-center p-5 mb-4 rounded" 
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <h6 class="mb-3">Current Balance</h6>
                            <h1 class="display-4 mb-0">{{number_format(auth()->user()->getAttribute('balance') ?? 0, 2)}} AZN</h1>
                        </div>

                        <div class="text-center">
                            <a href="{{route('profile.edit', auth()->id())}}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus-circle"></i> Add Balance
                            </a>
                        </div>
                    </div>

                    <!-- Orders Tab -->
                    <div class="tab-pane fade" id="pills-order" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><i class="fas fa-shopping-cart text-primary"></i> My Orders</h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-hashtag"></i> Number</th>
                                        <th><i class="fas fa-cog"></i> Service</th>
                                        <th><i class="fas fa-calendar"></i> Date</th>
                                        <th><i class="fas fa-info-circle"></i> Status</th>
                                        <th><i class="fas fa-file"></i> Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td>
                                                <strong class="text-primary">{{$order->getAttribute('code')}}</strong>
                                            </td>
                                            <td>{{$order->getAttribute('service')}}</td>
                                            <td>
                                                <small>{{$order->getAttribute('created_at')->format('d.m.Y H:i')}}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{$order->getAttribute('status') === 'done' ? 'success' : ($order->getAttribute('status') === 'pending' ? 'warning' : 'info')}}">
                                                    {{trans('translates.orders.statuses.'.$order->getAttribute('status'))}}
                                                </span>
                                            </td>
                                            <td>
                                                @if($order->getAttribute('result') !== null)
                                                    <a class="btn btn-sm btn-outline-primary" 
                                                       href="{{route('order-result.download', $order)}}"
                                                       title="Download Result">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">No orders found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($orders->hasPages())
                            <div class="mt-4">
                                {{$orders->appends(request()->input())->links()}}
                            </div>
                        @endif
                    </div>

                    <!-- Transactions Tab -->
                    <div class="tab-pane fade" id="pills-transactions" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><i class="fas fa-exchange-alt text-primary"></i> Transactions</h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-hashtag"></i> Number</th>
                                        <th><i class="fas fa-tag"></i> Type</th>
                                        <th><i class="fas fa-calendar"></i> Date</th>
                                        <th><i class="fas fa-money-bill"></i> Amount</th>
                                        <th><i class="fas fa-check-circle"></i> Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong class="text-primary">TRN48745684</strong></td>
                                        <td>Balance Top-up</td>
                                        <td><small>29.01.2023 15:45</small></td>
                                        <td><strong class="text-success">+45 AZN</strong></td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                    </tr>
                                    <!-- Add more transaction rows here -->
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center py-4">
                            <p class="text-muted">More transactions will appear here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Tab switching
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // Add any tab-specific logic here
    });
});
</script>

<style>
.info-section .info-item {
    transition: all 0.3s ease;
}
.info-section .info-item:hover {
    padding-left: 10px;
}
.balance-card {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.balance-display {
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}
.nav-pills .nav-link {
    border-radius: 0;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}
.nav-pills .nav-link:hover {
    background: #f8f9fa;
    padding-left: 20px;
}
.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.table-hover tbody tr:hover {
    background: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
}
</style>
@endsection
