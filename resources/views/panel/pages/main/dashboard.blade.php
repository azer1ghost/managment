@extends('layouts.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">

                    <nav>
                        <ul>
                            <li>
                                <a href="{{route('account')}}">Account</a>
                            </li>
                            <li>
                                <a href="{{route('signature-select-company')}}">Email Signature</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
