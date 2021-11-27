@extends('layouts.main')

@section('title', __('translates.navbar.customer_engagement'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.customer_engagement')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('customer-engagement.index')}}">
        <div class="row d-flex justify-content-between mb-2">

            <div class="col-8 col-md-3 pl-3 mb-3">
                <select name="company" class="custom-select">
                    <option value="">@lang('translates.fields.company') @lang('translates.placeholders.choose')</option>
                    @foreach($companies as $company)
                        <option @if(request()->get('company') == $company->id) selected @endif value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-8 col-md-3 mr-md-auto mb-3">
                <select name="limit" class="custom-select">
                    @foreach([25, 50, 100] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto mb-3">
                @can('create', App\Models\CustomerEngagement::class)
                    <a class="btn btn-outline-success" href="{{route('customer-engagement.create')}}">@lang('translates.buttons.create')</a>
                @endcan
            </div>
            <table class="table table-responsive-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('translates.fields.user')</th>
                    <th scope="col">@lang('translates.fields.company')</th>
                    <th scope="col">@lang('translates.fields.actions')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($customer_engagements as $customer_engagement)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$customer_engagement->getRelationValue('user')->getFullnameWithPositionAttribute()}}</td>
                        <td>{{$customer_engagement->getRelationValue('company')->getAttribute('name')}}</td>
                        <td>
                            <div class="btn-sm-group">
                                @can('view', $customer_engagement)
                                    <a href="{{route('customer-engagement.show', $customer_engagement)}}" class="btn btn-sm btn-outline-primary"> <i class="fal fa-eye"></i></a>
                                @endcan

                                @can('update', $customer_engagement)
                                    <a href="{{route('customer-engagement.edit', $customer_engagement)}}" class="btn btn-sm btn-outline-success"> <i class="fal fa-pen"></i></a>
                                @endcan

                                @can('delete', $customer_engagement)
                                    <a href="{{route('customer-engagement.destroy', $customer_engagement)}}" delete data-name="{{$customer_engagement->getAttribute('name')}}" class="btn btn-sm btn-outline-danger"> <i class="fal fa-trash"></i> </a>
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
                {{$customer_engagements->appends(request()->input())->links()}}
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        $('select').change(function () {
            this.form.submit();
        });
    </script>
@endsection
