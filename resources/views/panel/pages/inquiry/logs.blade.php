@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('inquiry.index')">
            @lang('translates.navbar.inquiry')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('inquiry.show', $inquiry)">
            {{ $inquiry->getAttribute('code')}}
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            Logs
        </x-bread-crumb-link>
    </x-bread-crumb>
{{--    <pre>{!! print_r($logs, true) !!}</pre>--}}
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
                    <tr>
                        <td>
                            <p><strong>User: </strong> {{$log->user->name}} {{$log->user->surname}}</p>
                            <p><strong>Date: </strong> {{$log->created_at}}</p>
                            <p><strong>IP: </strong> {{$log->ip_address}}</p>
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

