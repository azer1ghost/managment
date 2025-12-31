@extends('pages.transit.layout')

@section('title', __('transit.title') . ' | ' . __('transit.account'))

@section('content')
<div class="row gutters-sm">
    <div class="col-md-4 mb-4">
        <div class="transit-card">
            <div class="card-body text-center">
                    <div class="d-flex flex-column align-items-center">
                        <div class="profile-avatar mb-3 position-relative">
                            <div class="avatar-ring"></div>
                            <img src="{{asset('assets/images/diamond-green.png')}}" alt="Profile" 
                                 class="rounded-circle pulse-animation" width="150" height="150" 
                                 style="object-fit: cover; border: 5px solid rgba(102, 126, 234, 0.3); 
                                        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);">
                            <div class="avatar-badge">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                        </div>
                    <div class="mt-3">
                        <h4 class="mb-1">{{transit_user() ? transit_user()->name : 'N/A'}}</h4>
                        <p class="text-secondary mb-1">
                            <i class="fas fa-id-card"></i> {{transit_user() ? (transit_user()->voen ?: 'N/A') : 'N/A'}}
                        </p>
                        <p class="text-muted mb-3">
                            <i class="fas fa-envelope"></i> {{transit_user() ? transit_user()->email : 'N/A'}}
                        </p>
                        <div class="balance-card p-4 rounded mb-3 glow" 
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                    color: white; 
                                    box-shadow: 0 15px 50px rgba(102, 126, 234, 0.5);
                                    position: relative;
                                    overflow: hidden;">
                            <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; 
                                        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
                                        animation: rotate 10s linear infinite;"></div>
                            <div style="position: relative; z-index: 1;">
                                <small class="d-block mb-2" style="opacity: 0.9; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                                    <i class="fas fa-wallet me-2"></i>{{ __('transit.profile.current_balance') }}
                                </small>
                                <h2 class="mb-0 pulse-animation" style="font-weight: 800; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                                    {{number_format(transit_user() ? (transit_user()->balance ?? 0) : 0, 2)}} <small style="font-size: 0.6em;">AZN</small>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="transit-card mt-3">
            <div class="card-body p-0">
                <nav class="nav flex-column nav-pills" id="profileTabs" role="tablist">
                    <a class="nav-link active" id="tab-account" data-toggle="tab" href="#pills-account" role="tab">
                        <i class="fas fa-user"></i> {{ __('transit.nav.account') }}
                    </a>
                    <a class="nav-link" id="tab-balance" data-toggle="tab" href="#pills-balance" role="tab">
                        <i class="fas fa-wallet"></i> {{ __('transit.nav.balance') }}
                    </a>
                    <a class="nav-link" id="tab-order" data-toggle="tab" href="#pills-order" role="tab">
                        <i class="fas fa-shopping-cart"></i> {{ __('transit.nav.orders') }}
                    </a>
                    <a class="nav-link" id="tab-transactions" data-toggle="tab" href="#pills-transactions" role="tab">
                        <i class="fas fa-exchange-alt"></i> {{ __('transit.nav.transactions') }}
                    </a>
                    <a class="nav-link" href="{{ route('service') }}">
                        <i class="fas fa-home"></i> {{ __('transit.nav.home') }}
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
                            <h4 class="mb-0"><i class="fas fa-user text-primary"></i> {{ __('transit.profile.account_info') }}</h4>
                            <a href="{{route('profile.edit', transit_id())}}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> {{ __('transit.profile.edit_profile') }}
                            </a>
                        </div>

                        <div class="info-section">
                            <div class="info-item mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-circle text-primary me-2 fa-lg"></i>
                                    <h6 class="mb-0 text-muted">Full Name</h6>
                                </div>
                                <p class="mb-0 fs-5">{{transit_user() ? transit_user()->name : 'N/A'}}</p>
                            </div>

                            <div class="info-item mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope text-primary me-2 fa-lg"></i>
                                    <h6 class="mb-0 text-muted">Email</h6>
                                </div>
                                <p class="mb-0 fs-5">{{transit_user() ? transit_user()->email : 'N/A'}}</p>
                            </div>

                            <div class="info-item mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-id-card text-primary me-2 fa-lg"></i>
                                    <h6 class="mb-0 text-muted">VOEN</h6>
                                </div>
                                <p class="mb-0 fs-5">{{transit_user() ? (transit_user()->voen ?: 'Not provided') : 'Not provided'}}</p>
                            </div>

                            <div class="info-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-phone text-primary me-2 fa-lg"></i>
                                    <h6 class="mb-0 text-muted">Phone Number</h6>
                                </div>
                                <p class="mb-0 fs-5">{{transit_user() ? (transit_user()->phone ?: 'Not provided') : 'Not provided'}}</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <a href="{{route('profile.edit', transit_id())}}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> {{ __('transit.profile.edit_profile') }}
                            </a>
                            <a href="{{ route('logout') }}" class="btn btn-danger" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> {{ __('transit.button.logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>

                    <!-- Balance Tab -->
                    <div class="tab-pane fade" id="pills-balance" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><i class="fas fa-wallet text-primary"></i> {{ __('transit.nav.balance') }}</h4>
                        </div>

                        <div class="balance-display text-center p-5 mb-4 rounded glow" 
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                    color: white;
                                    position: relative;
                                    overflow: hidden;">
                            <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; 
                                        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
                                        animation: rotate 15s linear infinite;"></div>
                            <div style="position: relative; z-index: 1;">
                                <h6 class="mb-3" style="opacity: 0.9; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">
                                    <i class="fas fa-wallet me-2"></i>{{ __('transit.profile.current_balance') }}
                                </h6>
                                <h1 class="display-4 mb-0 pulse-animation" style="font-weight: 900; text-shadow: 0 5px 20px rgba(0,0,0,0.3);">
                                    {{number_format(transit_user() ? (transit_user()->balance ?? 0) : 0, 2)}} <small style="font-size: 0.5em;">AZN</small>
                                </h1>
                            </div>
                        </div>

                            <div class="text-center">
                                <a href="{{route('profile.edit', transit_id())}}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus-circle"></i> {{ __('transit.button.add_balance') }}
                                </a>
                            </div>
                    </div>

                    <!-- Orders Tab -->
                    <div class="tab-pane fade" id="pills-order" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><i class="fas fa-shopping-cart text-primary"></i> {{ __('transit.profile.my_orders') }}</h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-hashtag"></i> {{ __('transit.profile.order_number') }}</th>
                                        <th><i class="fas fa-cog"></i> {{ __('transit.profile.service') }}</th>
                                        <th><i class="fas fa-calendar"></i> {{ __('transit.profile.date') }}</th>
                                        <th><i class="fas fa-info-circle"></i> {{ __('transit.profile.status') }}</th>
                                        <th><i class="fas fa-file"></i> {{ __('transit.profile.result') }}</th>
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
                                                @php
                                                    $status = $order->getAttribute('status');
                                                    $isPaid = $order->getAttribute('is_paid');
                                                    $statusText = '';
                                                    $statusClass = '';
                                                    
                                                    if ($status == 1 && !$isPaid) {
                                                        $statusText = 'Taslak';
                                                        $statusClass = 'secondary';
                                                    } elseif ($status == 1 && $isPaid) {
                                                        $statusText = trans('translates.orders.statuses.1');
                                                        $statusClass = 'warning';
                                                    } elseif ($status == 2) {
                                                        $statusText = trans('translates.orders.statuses.2');
                                                        $statusClass = 'info';
                                                    } elseif ($status == 3) {
                                                        $statusText = trans('translates.orders.statuses.3');
                                                        $statusClass = 'success';
                                                    } elseif ($status == 4) {
                                                        $statusText = trans('translates.orders.statuses.4');
                                                        $statusClass = 'danger';
                                                    } else {
                                                        $statusText = trans('translates.orders.statuses.' . $status);
                                                        $statusClass = 'info';
                                                    }
                                                @endphp
                                                <span class="badge bg-{{$statusClass}}">
                                                    {{$statusText}}
                                                </span>
                                            </td>
                                            <td>
                                                @if($order->getAttribute('declaration'))
                                                    <form id="download-declaration-form-{{$order->id}}" action="{{ route('orders.download') }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="document" value="{{$order->getAttribute('declaration')}}">
                                                    </form>
                                                    <a class="btn btn-sm btn-outline-success" 
                                                       href="#"
                                                       onclick="event.preventDefault(); document.getElementById('download-declaration-form-{{$order->id}}').submit();"
                                                       title="Bəyannaməni yüklə">
                                                        <i class="fas fa-download"></i> Bəyannamə
                                                    </a>
                                                @elseif($order->getAttribute('result') !== null)
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
                                                <p class="text-muted mb-0">{{ __('transit.profile.no_orders') }}</p>
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
                            <h4 class="mb-0"><i class="fas fa-exchange-alt text-primary"></i> {{ __('transit.profile.transactions') }}</h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-hashtag"></i> {{ __('transit.profile.transaction_number') }}</th>
                                        <th><i class="fas fa-tag"></i> {{ __('transit.profile.transaction_type') }}</th>
                                        <th><i class="fas fa-calendar"></i> {{ __('transit.profile.date') }}</th>
                                        <th><i class="fas fa-money-bill"></i> {{ __('transit.profile.amount') }}</th>
                                        <th><i class="fas fa-check-circle"></i> {{ __('transit.profile.transaction_status') }}</th>
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
.table-hover tbody tr {
    transition: all 0.3s ease;
}
.table-hover tbody tr:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%) !important;
    transform: translateX(10px) scale(1.02);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
}

.avatar-ring {
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    border: 3px solid transparent;
    border-top-color: #667eea;
    border-right-color: #764ba2;
    border-radius: 50%;
    animation: spin 3s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.avatar-badge {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: white;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    animation: bounce 2s ease-in-out infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.1);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.5);
}
</style>
@endsection
