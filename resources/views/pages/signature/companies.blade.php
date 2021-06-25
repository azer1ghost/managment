@extends('layouts.signature')

@section('style')
    <style>
        .company{
            height: 300px;
            background-color: #ffffff;
            border-radius: 4px;
            padding: 10px;
            margin: 12px;
            text-decoration: none;
            overflow: hidden;
            border: 1px solid #cccccc;
            transition: box-shadow 0.2s ease-in-out, transform 0.35s ease-in-out;
        }

        .company:hover {
            box-shadow: 0 11px 11px 0 rgba(0, 0, 0,.16);
            transform: translateY(-5px);
        }

        .company:hover .company img{
            box-shadow: 0 11px 11px 0 rgba(0, 0, 0,.16);
            transform: translateY(-5px);
        }

        .gradient {
            background: linear-gradient(135deg, rgba(101,186,0,1) 0%, rgba(255,0,35,1) 100%);
            transition: background 2.2s ease-in-out;
        }

        .gradient:hover {
            background: linear-gradient(135deg, rgba(0,138,255,1) 0%, rgba(168,0,186,1) 100%);
        }

        .company img{
            max-width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row d-flex align-content-center vh-100">
            @foreach(["broker",'logistics','mobex'] as $company)
               <div class="col-md-3">
                   <div class="card gradient company d-flex justify-content-center align-items-center">
                       <img class="card-img-top" src="{{asset("images/logos/$company.png")}}" alt="Logo">
                   </div>
               </div>
            @endforeach
        </div>
    </div>
@endsection