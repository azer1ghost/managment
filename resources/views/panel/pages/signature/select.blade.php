@extends('layouts.main')

@section('title', __('translates.navbar.signature'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.signature')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="container my-5">
        <div class="row d-flex align-content-center">
            @foreach($companies as $company)
               <div class="col-md-3 mb-3">
                   <div class="company card text-white card-has-bg click-col" style="background-image:url('https://source.unsplash.com/600x900/?{{$company->keywords}}');">
                       <img class="card-img d-none" src="https://source.unsplash.com/600x900/?{{$company->getAttribute('keywords')}}" alt="{{$company->getAttribute('name')}}">
                       <div class="card-img-overlay d-flex flex-column">
                           <div class="card-body">
                               <small class="card-meta mb-2">Thought Leadership</small>
                               <h4 class="card-title mt-0 text-capitalize"><a class="text-white" href="{{route('companies.show',$company)}}">{{$company->getAttribute('name')}}</a></h4>
                               <img class="img-fluid mt-4" src="https://mobilgroup.az/signature/{{$company->getAttribute('logo')}}" alt="{{$company->getAttribute('name')}}">
                           </div>
                           <div class="card-footer text-center">
                               <a href="{{route('signature', $company)}}" class="btn btn-outline-orange text-uppercase w-100">Get My Signature</a>
                           </div>
                       </div>
                   </div>
               </div>
            @endforeach
        </div>
    </div>
@endsection