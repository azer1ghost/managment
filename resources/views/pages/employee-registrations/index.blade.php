@extends('layouts.main')

@section('title', __('translates.navbar.commands'))

@section('style')
    <style>
        /* Tablo borderı */
        table.border {
            border-collapse: collapse;
        }

        table.border th,
        table.border td {
            border: 1px solid black;
            /*padding: 5px;*/
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            MB-P-023/04 Əmrlərin Qeydiyyatı Jurnalı
        </x-bread-crumb-link>
    </x-bread-crumb>

    <form action="{{ route('employee-registrations.index') }}">
        <div class="col-12 col-md-6 mb-3">
            <div class="input-group mb-3">
                <input type="search" name="search" value="{{ request()->get('search') }}" class="form-control"
                       placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username"
                       aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                    <a class="btn btn-outline-danger d-flex align-items-center"
                       href="{{ route('employee-registrations.index') }}"><i class="fal fa-times"></i></a>
                </div>
            </div>
        </div>
    </form>

    <div class="col-12 p-5 table-body">
        <table class="border">
            <thead>
            <tr>
                <th></th> <!-- Boş hücre -->
                @php
                    $currentDate = now()->startOfMonth();
                    $endOfMonth = now()->endOfMonth();
                @endphp
                @while($currentDate <= $endOfMonth)
                    <th>{{ $currentDate->format('Y-m-d') }}</th>
                    @php
                        $currentDate = $currentDate->addDay();
                    @endphp
                @endwhile
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->getAttribute('fullname') }}</td>
                    @php
                        $currentDate = now()->startOfMonth();
                    @endphp
                    @while($currentDate <= $endOfMonth)
                        <td>
                            @php
                                $isChecked = \App\Models\EmployeeRegistration::where('user_id', $user->id)->where('date', $currentDate->format('Y-m-d'))->latest()->first();
                            @endphp
                            <input type="checkbox" style="font-size: 23px" class="toggle-checkbox"
                                   data-user-id="{{ $user->id }}" data-date="{{ $currentDate->format('Y-m-d') }}"
                                   @if($isChecked && $isChecked->status == '+')
                                       checked
                                    @endif
                            >
                            <input type="hidden" name="kayitlar[{{ $user->id }}][{{ $currentDate->format('Y-m-d') }}]"
                                   value="">
                        </td>
                        @php
                            $currentDate = $currentDate->addDay();
                        @endphp
                    @endwhile
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.toggle-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                var userId = this.getAttribute('data-user-id');
                var date = this.getAttribute('data-date');
                var status = this.checked ? '+' : '-';

                fetch('{{ route("employee-registrations.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        date: date,
                        value: status,
                    }),
                })
                    .then(function (response) {
                        if (response.ok) {
                            console.log('Durum güncellendi.');

                            // Yeniden değişiklik yapılınca verileri güncellemek için AJAX isteği yapın
                            restoreCheckboxStatus();
                        } else {
                            console.log('Durum güncellenirken bir hata oluştu.');
                        }
                    })
                    .catch(function (error) {
                        console.log('İstek gönderilirken bir hata oluştu:', error);
                    });

                // Bu örnekte sadece konsola bir çıktı basıyoruz
                console.log('Kullanıcı ID:', userId);
                console.log('Tarih:', date);
                console.log('Durum:', status);
            });
        });

        // Sayfa yüklendiğinde checkbox durumlarını geri yüklemek için AJAX isteği yapın
        document.addEventListener('DOMContentLoaded', function () {
            restoreCheckboxStatus();
        });

        // Verileri geri yüklemek için AJAX isteği yapın ve checkbox durumlarını güncelleyin
        function restoreCheckboxStatus() {
            var checkboxes = document.querySelectorAll('.toggle-checkbox');
            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    var userId = checkbox.getAttribute('data-user-id');
                    var date = checkbox.getAttribute('data-date');
                    var status = checkbox.checked ? '+' : '';

                    // Veriyi yerel veritabanından al
                    fetch('{{ route("employee-registrations.getStatus") }}' + '?user_id=' + userId + '&date=' + date)
                        .then(function (response) {
                            if (response.ok) {
                                return response.json(); // Verileri JSON formatında al
                            } else {
                                throw new Error('Veri alınamadı.'); // Hata fırlat
                            }
                        })
                        .then(function (response) {
                            var data = response.data;
                            var updatedStatus = data[userId][date] === '+' ? '-' : '+';
                            checkbox.checked = (updatedStatus === '+');
                            checkbox.nextElementSibling.value = updatedStatus; // Gizli alana değeri atama
                        })
                        .catch(function (error) {
                            console.log('Hata:', error.message);
                        });

                    // Diğer işlemler...
                });
            });
        }
    </script>
@endsection