@extends('layouts.main')

@section('title', __('translates.navbar.registration_logs'))
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
            @lang('translates.navbar.registration_logs')
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                @can('create', \App\Models\RegistrationLog::class)
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('registration-logs.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Sənədin daxilolma tarixi</th>
                        <th scope="col">Sənədi göndərən</th>
                        <th scope="col">Sənədin nömrəsi</th>
                        <th scope="col">Sənədin qısa məzmunu</th>
                        <th scope="col">Dərkənar</th>
                        <th scope="col">İcraçı</th>
                        <th scope="col">Alınma barədə tarix</th>
                        <th scope="col">@lang('translates.parameters.types.operation')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($registrationLogs as $regLog)
                         <tr>

                            <td>{{$loop->iteration}}</td>
                            <td>{{$regLog->getAttribute('arrived_at')}}</td>
                            <td>{{$regLog->getAttribute('sender')}}</td>
                            <td>{{$regLog->getAttribute('number')}}</td>
                            <td>{{$regLog->getAttribute('description')}}</td>
                            <td>{{$regLog->getRelationValue('performer')->getFullnameWithPositionAttribute()}}</td>
                            <td>{{$regLog->getRelationValue('receiver')->getFullnameWithPositionAttribute()}}</td>
                            <td>{{$regLog->getAttribute('received_at')}}</td>
                             @can('update', App\Models\RegistrationLog::class)
                                 <td>
                                     <div class="btn-sm-group">
                                         <a href="{{route('registration-logs.show', $regLog)}}" class="btn btn-sm btn-outline-primary">
                                             <i class="fal fa-eye"></i>
                                         </a>
                                         <a href="{{route('registration-logs.edit', $regLog)}}" class="btn btn-sm btn-outline-success">
                                             <i class="fal fa-pen"></i>
                                         </a>
                                         <a href="{{route('registration-logs.destroy', $regLog)}}" delete data-name="{{$regLog->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
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
                    <div class="float-right">
                        {{$registrationLogs->appends(request()->input())->links()}}
                    </div>
            </div>
@endsection