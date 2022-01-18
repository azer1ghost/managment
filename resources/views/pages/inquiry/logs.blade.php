@extends('layouts.main')

@section('title', 'Logs')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="back()">
            <i class="fas fa-arrow-left"></i> Back
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="row m-0">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Date</td>
                    <td>Data</td>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr style="border-bottom: 1.5px solid gray">
                        <td>
                            <p><strong>URL: </strong> {{$log->url}}</p>
                            <p><strong>User: </strong> {{$log->user->name}} {{$log->user->surname}}</p>
                            <p><strong>Date: </strong> {{$log->created_at}}</p>
                            <p><strong>IP: </strong> {{$log->ip_address}}</p>
                            @php($user_agent = Browser::parse($log->user_agent))
                            <p><strong>Device: </strong>{{$user_agent->deviceFamily()}}</p>
                            <p><strong>Browser: </strong>{{$user_agent->browserName()}}</p>
                            <p><strong>OS: </strong>{{$user_agent->platformName()}} ({{$user_agent->platformFamily()}})</p>
                            <p><strong>Event: </strong> {{$log->event}}</p>
                        </td>
                        <td>
                            @switch($log->event)
                                @case('created')
                                    @foreach($log->getData() as $key => $value)
                                        <p><strong>{{str_title($key)}}: </strong> {{$value}}</p>
                                    @endforeach
                                    @break
                                @case('updated')
                                    @foreach($log->getData() as $key => $value)
                                        <p><strong>{{str_title($key)}}: </strong> {{$value}}</p>
                                    @endforeach
                                    @break
                                @case('synced')
                                    @foreach($log->getPivotData()['properties'] as $parameter)
                                        <p>
                                            <strong>{{str_title(\App\Models\Parameter::find($parameter['parameter_id'])->name)}}: </strong>
                                            @php($val = $inquiry->getParameterById($parameter['parameter_id']))
                                            {{optional($val)->text ?? optional($val)->value}}
                                        </p>
                                    @endforeach
                                    @break
                            @endswitch
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

