@extends('layouts.main')

@section('title', __('translates.navbar.services'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.services')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('services.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('services.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <select name="limit" class="custom-select">
                    @foreach([10, 25, 50] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            @can('create', App\Models\Service::class)
                <div class="col-3">
                    <a class="btn btn-outline-success float-right" href="{{route('services.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.fields.detail')</th>
                        <th scope="col">@lang('translates.columns.department')</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($services as $service)
                        <tr>
                            <th scope="row">{{$loop->iteration}}.</th>
                            <td><i class="{{$service->getAttribute('icon')}}"></i> {{$service->getAttribute('name')}}</td>
                            <td>{{$service->getAttribute('detail')}}</td>
                            <td>{{$service->getRelationValue('department')->getAttribute('name')}}</td>
                            <td>{{$service->getRelationValue('company')->getAttribute('name')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $service)
                                        <a href="{{route('services.show', $service)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $service)
                                        <a href="{{route('services.edit', $service)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $service)
                                        <a href="{{route('services.destroy', $service)}}" delete data-name="{{$service->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @foreach($service->services as $subservice)
                            <tr>
                                <td></td>
                                <td><i class="{{$subservice->getAttribute('icon')}} fa-2x pr-2"></i> {{$subservice->getAttribute('name')}}</td>
                                <td colspan="3">{{$subservice->getAttribute('detail')}}</td>
                                <td>
                                    <div class="btn-sm-group">
                                        @can('view', $subservice)
                                            <a href="{{route('services.show', $subservice)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('update', $subservice)
                                            <a href="{{route('services.edit', $subservice)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $subservice)
                                            <a href="{{route('services.destroy', $subservice)}}" delete data-name="{{$subservice->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                                <i class="fal fa-trash"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <th colspan="6">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="float-right">
                    {{$services->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        $('select').change(function(){
            this.form.submit();
        });
    </script>
@endsection