@extends('layouts.main')

@section('title', __('translates.navbar.foreign_relation'))
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
            @lang('translates.navbar.intern_relation')
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                <table  class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Department</th>
                        <th scope="col">#</th>
                        <th scope="col">Müraciət Edən Şəxs</th>
                        <th scope="col">Əlaqə Saxlanılacaq Hal</th>
                        <th scope="col">Əlaqə Saxlanılacaq Şəxs</th>
                        <th scope="col">Əlaqə Vasitəsi</th>
                        <th scope="col">Əlaqə Zamanı</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($internalRelations as $internalRelation)
                        <tr>
                            <th>{{$internalRelation->getRelationValue('departments')->getAttribute('name')}}</th>
                            <th>{{$internalRelation->getAttribute('ordering')}}</th>
                            <td>{{$internalRelation->getAttribute('applicant')}}</td>
                            <td>{{$internalRelation->getAttribute('case')}}</td>
                            <td class="overflow-wrap-hack">
                                <div class="content">
                                    {{$internalRelation->getAttribute('user_id') == null ? $internalRelation->getAttribute('reciever') : $internalRelation->getRelationValue('users')->getFullnameWithPositionAttribute()}}
                                </div>
                            </td>
                            <td style="word-wrap: break-word;">{{$internalRelation->getAttribute('tool')}}</td>
                            <td>{{$internalRelation->getAttribute('contact_time')}}</td>
                            @can('update', App\Models\InternalRelation::class)
                                <td>
                                    <div class="btn-sm-group">
                                            <a href="{{route('internal-relations.show', $internalRelation)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                            <a href="{{route('internal-relations.edit', $internalRelation)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                            <a href="{{route('internal-relations.destroy', $internalRelation)}}" delete data-name="{{$internalRelation->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
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
    @can('create', App\Models\InternalRelation::class)
        <div class="col-12">
            <a class="btn btn-outline-success float-right" href="{{route('internal-relations.create')}}">@lang('translates.buttons.create')</a>
        </div>
    @endcan

@endsection