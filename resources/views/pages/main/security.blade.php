@extends('layouts.main')

@section('title', 'Security')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link is-current="1">
            Security
        </x-bread-crumb-link>
    </x-bread-crumb>
    <table class="table table-responsive-sm table-hover">
        <thead>
            <tr>
                <td>Clients</td>
                <td>IP</td>
                <td>Location</td>
                <td>First Login</td>
                <td>Most Recent activity</td>
                <td>Sesion Status</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        @foreach($user->devices as $device)
            <tr>
                <td>{{$device->getAttribute('device')}}</td>
                <td>{{$device->getAttribute('ip')}}</td>
                <td>{{$device->getAttribute('location')}}</td>
                <td>{{$device->getAttribute('created_at')}}</td>
                <td>{{$device->getAttribute('last_active_at')}}</td>
                <td>
                    <div class="badge badge-success">Current</div>
                </td>
                <td>
                    <button class="btn btn-sm btn-default">
                        <i class="fa fa-portal-exit pr-1"></i> Logout
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection