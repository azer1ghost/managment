@extends('layouts.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Your email signature for <b>{{$company->name}} </b></div>
                    <div class="card-body row">
                        <div>
                            <section class="border border-info">
                                @include('panel.pages.signature.template.template1', ['company'=> $company, 'user' => auth()->user()])
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
