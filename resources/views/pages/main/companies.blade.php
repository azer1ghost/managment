@extends('layouts.main')

@section('style')
    <style>

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row d-flex align-content-center vh-100">
            <div class="col-md-12 mb-5">
                <h2 class="text-center">Please select your company</h2>
            </div>
            @foreach($companies as $company)
               <div class="col-md-3">
                   <div class="company card text-white card-has-bg click-col" style="background-image:url('https://source.unsplash.com/600x900/?{{$company->keywords}}');">
                       <img class="card-img d-none" src="https://source.unsplash.com/600x900/?{{$company->keywords}}" alt="{{$company->name}}">
                       <div class="card-img-overlay d-flex flex-column">
                           <div class="card-body">
                               <small class="card-meta mb-2">Thought Leadership</small>
                               <h4 class="card-title mt-0 text-capitalize"><a class="text-white" href="{{route('register')}}">{{$company->name}}</a></h4>
                               <img class="img-fluid mt-4" src="{{asset('images/'. $company->logo)}}" alt="{{$company->name}}">
                           </div>
                           <div class="card-footer text-center">
                               <a href="{{route('register')}}" class="btn btn-outline-orange text-uppercase w-50">Join</a>
                           </div>
                       </div>
                   </div>
               </div>
            @endforeach
        </div>
    </div>
@endsection