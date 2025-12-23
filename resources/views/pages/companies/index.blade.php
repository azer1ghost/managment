@extends('layouts.main')

@section('title', __('translates.navbar.company'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.company')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="float-right mb-2">
        @can('create', App\Models\Company::class)
            <a class="btn btn-outline-success" href="{{route('companies.create')}}">@lang('translates.buttons.create')</a>
        @endcan
    </div>
    <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">@lang('translates.fields.logo')</th>
                <th scope="col">@lang('translates.fields.company')</th>
                <th scope="col">@lang('translates.fields.actions')</th>
            </tr>
        </thead>
        <tbody>
        @forelse($companies as $company)
        <tr>
            <th scope="row">{{$loop->iteration}}</th>
            <td><img style="border-radius: 0px; width: 100px" src="{{asset("assets/images/{$company->getAttribute('logo')}")}}"></td>
            <td>{{$company->getAttribute('name')}}</td>
            <td>
                <div class="btn-sm-group">
                    @can('update', $company)
                        <button type="button" 
                                class="btn btn-sm {{ $company->has_no_vat ? 'btn-warning' : 'btn-info' }} toggle-vat-btn" 
                                data-company-id="{{ $company->id }}"
                                title="{{ $company->has_no_vat ? 'ƏDV-siz (basaraq ƏDV-li edin)' : 'ƏDV-li (basaraq ƏDV-siz edin)' }}">
                            <i class="fal {{ $company->has_no_vat ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                            {{ $company->has_no_vat ? 'ƏDV-siz' : 'ƏDV-li' }}
                        </button>
                    @endcan
                    @can('view', $company)
                        <a href="{{route('companies.show', $company)}}" class="btn btn-sm btn-outline-primary">
                            <i class="fal fa-eye"></i>
                        </a>
                    @endcan
                    @can('update', $company)
                        <a href="{{route('companies.edit', $company)}}" class="btn btn-sm btn-outline-success">
                            <i class="fal fa-pen"></i>
                        </a>
                    @endcan
                    @can('delete', $company)
                        <a href="{{route('companies.destroy', $company)}}" delete data-name="{{$company->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                            <i class="fal fa-trash"></i>
                        </a>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <th colspan="4">
                <div class="row justify-content-center m-3">
                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                </div>
            </th>
        </tr>
        @endforelse
        </tbody>
    </table>
    <div class="float-right">
        {{$companies->appends(request()->input())->links()}}
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.toggle-vat-btn').on('click', function() {
            const btn = $(this);
            const companyId = btn.data('company-id');
            const originalHtml = btn.html();
            
            // Loading state
            btn.prop('disabled', true);
            btn.html('<i class="fal fa-spinner fa-spin"></i> Yüklənir...');
            
            $.ajax({
                url: '{{ route("companies.toggle-vat", ":id") }}'.replace(':id', companyId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update button appearance
                        if (response.has_no_vat) {
                            btn.removeClass('btn-info').addClass('btn-warning');
                            btn.html('<i class="fal fa-times-circle"></i> ƏDV-siz');
                            btn.attr('title', 'ƏDV-siz (basaraq ƏDV-li edin)');
                        } else {
                            btn.removeClass('btn-warning').addClass('btn-info');
                            btn.html('<i class="fal fa-check-circle"></i> ƏDV-li');
                            btn.attr('title', 'ƏDV-li (basaraq ƏDV-siz edin)');
                        }
                        
                        // Show success message
                        if (typeof showNotify === 'function') {
                            showNotify('success', response.message);
                        } else {
                            alert(response.message);
                        }
                    }
                },
                error: function(xhr) {
                    btn.html(originalHtml);
                    alert('Xəta baş verdi. Zəhmət olmasa yenidən cəhd edin.');
                },
                complete: function() {
                    btn.prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection

