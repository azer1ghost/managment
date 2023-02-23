@extends('layouts.main')

@section('title', __('translates.navbar.access_rate'))
@section('style')
    <style>
        table {
            table-layout:fixed;
            width:100%;
        }
        td, th {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.access_rate')
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                @can('create', App\Models\AccessRate::class)
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('access-rates.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                @can('create', App\Models\AccessRate::class)
                    <div class="col-12">
                        <a class="btn btn-outline-primary float-left" href="{{route('folders.index')}}">@lang('translates.navbar.folder')</a>
                    </div>
                @endcan
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Qovluq Adı</th>
                        <th scope="col">Tərkibi</th>
                        <th scope="col">Oxuya Bilər</th>
                        <th scope="col">Dəyişiklik edə bilər</th>
                        <th scope="col">Çap edə bilər</th>
                        <th scope="col">Vəzifə</th>
                        <th scope="col">Əməliyyatlar</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @forelse($accessRates as $accessRate)
                            <tr id="item-{{$accessRate->getAttribute('id')}}">
                            <th>{{$loop->iteration}}</th>
                                <td>{{$accessRate->getRelationValue('folders')->getAttribute('name')}}</td>
                                <td>{{$accessRate->getAttribute('composition')}}</td>
                                <td>@if($accessRate->getAttribute('is_readonly') == 1) + @else - @endif</td>
                                <td>@if($accessRate->getAttribute('is_change') == 1) + @else - @endif</td>
                                <td>@if($accessRate->getAttribute('is_print') == 1) + @else - @endif</td>
                                <th>{{$accessRate->getRelationValue('positions')->getAttribute('name')}}</th>
                            @can('update', App\Models\AccessRate::class)
                                <td>
                                    <div class="btn-sm-group">
                                            <a href="{{route('access-rates.show', $accessRate)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                            <a href="{{route('access-rates.edit', $accessRate)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                            <a href="{{route('access-rates.destroy', $accessRate)}}" delete data-name="{{$accessRate->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
                                                <i class="fal fa-trash"></i>
                                            </a>
                                    </div>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <th colspan="20">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
@endsection
