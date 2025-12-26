@extends('layouts.main')

@section('title', 'Transit Müştəri - ' . $customer->name)

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('transit-customers.index')">
            Transit Müştəriləri
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            {{$customer->name}}
        </x-bread-crumb-link>
    </x-bread-crumb>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="mb-3">{{$customer->name}}</h4>
                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope"></i> {{$customer->email}}
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-phone"></i> {{$customer->phone}}
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-id-card"></i> {{$customer->voen ?: 'Yoxdur'}}
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-globe"></i> {{$customer->country ?: 'Yoxdur'}}
                    </p>
                    <p class="mb-2">
                        <span class="badge badge-{{$customer->type == 'legal' ? 'info' : 'secondary'}}">
                            {{$customer->type == 'legal' ? 'Hüquqi' : 'Fiziki'}}
                        </span>
                    </p>
                    <hr>
                    <h5 class="text-success">
                        <i class="fas fa-wallet"></i> {{number_format($customer->balance, 2)}} AZN
                    </h5>
                    <p class="text-muted">
                        <small>Qeydiyyat: {{$customer->created_at->format('d.m.Y H:i')}}</small>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Sifarişlər</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sifariş №</th>
                                    <th>Xidmət</th>
                                    <th>Məbləğ</th>
                                    <th>Tarix</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td><strong>{{$order->code}}</strong></td>
                                        <td>{{$order->service}}</td>
                                        <td>{{number_format($order->amount, 2)}} AZN</td>
                                        <td>{{$order->created_at->format('d.m.Y H:i')}}</td>
                                        <td>
                                            @php
                                                $status = $order->status;
                                                $isPaid = $order->is_paid;
                                                $statusText = '';
                                                $statusClass = '';
                                                
                                                if ($status == 1 && !$isPaid) {
                                                    $statusText = 'Taslak';
                                                    $statusClass = 'secondary';
                                                } elseif ($status == 1 && $isPaid) {
                                                    $statusText = 'Gözləyir';
                                                    $statusClass = 'warning';
                                                } elseif ($status == 2) {
                                                    $statusText = 'İşlənir';
                                                    $statusClass = 'info';
                                                } elseif ($status == 3) {
                                                    $statusText = 'Hazırdır';
                                                    $statusClass = 'success';
                                                } elseif ($status == 4) {
                                                    $statusText = 'Tamamlanıb';
                                                    $statusClass = 'success';
                                                } else {
                                                    $statusText = trans('translates.orders.statuses.' . $status);
                                                    $statusClass = 'info';
                                                }
                                            @endphp
                                            <span class="badge bg-{{$statusClass}}">
                                                {{$statusText}}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Sifariş yoxdur</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($orders->hasPages())
                        <div class="mt-3">
                            {{$orders->links()}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <a href="{{route('transit-customers.index')}}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
            <a href="{{route('transit-customers.edit', $customer)}}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Redaktə et
            </a>
        </div>
    </div>
@endsection

