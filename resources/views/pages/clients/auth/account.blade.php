@extends('layouts.main')
@section('title', 'Login')
@section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ $client->fullname }}</div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">

                                </div>
                                <div class="col-md-8">
                                    <p><strong>Username:</strong> {{ $client->fullname }}</p>
                                    <p><strong>Voen:</strong> {{ $client->voen }}</p>
                                    <p><strong>@lang('translates.columns.phone'):</strong> {{ $client->phone1 }}</p>
                                    <p><strong>@lang('translates.columns.phone'):</strong> {{ $client->phone1 }}</p>
                                    <p><strong>Email:</strong> {{ $client->email1 }}</p>
                                    <p><strong>Email:</strong> {{ $client->email2 }}</p>
                                    <p><strong>Joined:</strong> {{ $client->created_at->format('F j, Y') }}</p>
                                    <a href="{{ route('client-logout') }}" onclick="event.preventDefault();
                                    document.getElementById('client-logout-form').submit();">
                                        <i class="fas fa-house-leave text-primary"></i>
                                        Logout
                                    </a>
                                    <form id="client-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@endsection