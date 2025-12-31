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
                                    <th>Bəyannamə</th>
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
                                        <td>
                                            @if($order->declaration)
                                                <form id="download-form-{{$order->id}}" action="{{ route('orders.download') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="document" value="{{$order->declaration}}">
                                                </form>
                                                <a class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('download-form-{{$order->id}}').submit();" title="Bəyannaməni yüklə">
                                                    <i class="fas fa-file-pdf"></i> Bax
                                                </a>
                                            @else
                                                <form action="{{ route('orders.upload-declaration', $order->id) }}" method="POST" enctype="multipart/form-data" class="declaration-upload-form" id="declaration-form-{{$order->id}}" data-order-id="{{$order->id}}">
                                                    @csrf
                                                    <div class="file-upload-wrapper">
                                                        <input type="file" name="declaration" id="declaration-{{$order->id}}" class="declaration-file-input" accept=".pdf,.jpg,.jpeg,.png" required style="display: none;">
                                                        <button type="button" class="btn btn-sm btn-primary mb-0" onclick="document.getElementById('declaration-{{$order->id}}').click();">
                                                            <i class="fas fa-upload"></i> Yüklə
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
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

@section('scripts')
<script>
$(document).ready(function() {
    $(document).on('change', '.declaration-file-input', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const fileInput = this;
        
        if (fileInput.files && fileInput.files[0]) {
            const fileSize = fileInput.files[0].size;
            const maxSize = 10 * 1024 * 1024; // 10MB
            
            if (fileSize > maxSize) {
                alert('Fayl ölçüsü 10MB-dan çox ola bilməz!');
                $(fileInput).val('');
                return false;
            }
            
            // Form submit
            form[0].submit();
        }
    });
});
</script>
@endsection

