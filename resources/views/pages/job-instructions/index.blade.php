@extends('layouts.main')

@section('title', trans('translates.navbar.job_instruction'))

@section('content')
    <style href="https://fonts.googleapis.com/css2?family=Lato&display=swap">
        h2 {
            background: #f5f7ff;
            border: 1px #F5F6FC solid;
            text-align: center;
            padding: 5px;
            color: #000000;
            cursor: pointer;
        }
        body {
            background: #e0e5ec;
        }
        h1 {
            position: relative;
            text-align: center;
            color: #353535;
            font-size: 50px;
            font-family: "Cormorant Garamond", serif;
        }

        p {
            font-family: 'Lato', sans-serif;
            font-weight: 300;
            text-align: center;
            font-size: 18px;
            color: #676767;
        }
        .frame {
            width: 90%;
            margin: 40px auto;
            text-align: center;
        }
        button {
            margin: 20px;
        }
        .custom-btn {
            width: auto;
            height: auto;
            color: #fff;
            border-radius: 5px;
            padding: 10px 25px;
            font-family: 'Lato', sans-serif;
            font-weight: 500;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
            box-shadow:inset 2px 2px 2px 0px rgba(255,255,255,.5),
            7px 7px 20px 0px rgba(0,0,0,.1),
            4px 4px 5px 0px rgba(0,0,0,.1);
            outline: none;
        }
        .btn-1 {
            width: 403px;
            height: 90px;
            padding: 30px;
            border: none;
            background: rgb(0, 21, 255);
            background: linear-gradient(0deg, rgb(8, 0, 255) 0%, rgb(2, 52, 251) 100%);
        }
        .btn-1:hover {
            color: #0915f0;
            background: transparent;
            box-shadow:none;
        }
        .btn-1:before,
        .btn-1:after{
            content:'';
            position:absolute;
            top:0;
            right:0;
            height:2px;
            width:0;
            background: #0915f0;
            box-shadow:
                    -1px -1px 5px 0px #fff,
                    7px 7px 20px 0px #0003,
                    4px 4px 5px 0px #0002;
            transition:400ms ease all;
        }
        .btn-1:after{
            right:inherit;
            top:inherit;
            left:0;
            bottom:0;
        }
        .btn-1:hover:before,
        .btn-1:hover:after{
            width:100%;
            transition:800ms ease all;
        }
    </style>
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.job_instruction')
        </x-bread-crumb-link>
    </x-bread-crumb>

    @foreach($jobInstructions as $jobInstruction)

        <button type="button" class="custom-btn btn-1" href="#instruction-{{$loop->iteration}}" data-toggle="modal" data-target="#instruction-{{$loop->iteration}}" data-jobInstruction="@json($jobInstruction)">
            {{$jobInstruction->getRelationValue('users')->getFullnameWithPositionAttribute()}}
        </button>

    <div class="modal fade bd-example-modal-lg" id="instruction-{{$loop->iteration}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{$jobInstruction->getRelationValue('users')->getFullnameWithPositionAttribute()}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                            <p>{!! $jobInstruction->getAttribute('instruction') !!}</p>
                </div>

                <div class="modal-footer">
                    @can('update', App\Models\JobInstruction::class)
                        <a href="{{route('job-instructions.edit', $jobInstruction)}}" class="btn btn-sm btn-outline-success">
                            <i class="fal fa-pen"></i>
                        </a>
                        <a href="{{route('job-instructions.destroy', $jobInstruction)}}" delete data-name="{{$jobInstruction->getRelationValue('users')->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                            <i class="fal fa-trash"></i>
                        </a>
                    @endcan
                </div>

            </div>
        </div>
    </div>
    @endforeach
    @can('create', App\Models\JobInstruction::class)
        <div class="col-12">
            <a class="btn btn-outline-success float-right"
               href="{{route('job-instructions.create')}}">@lang('translates.buttons.create')</a>
        </div>
    @endcan
    <div class="col-6">
        <div class="float-right">
            {{$jobInstructions->appends(request()->input())->links()}}
        </div>
    </div>
@endsection

{{--@section('scripts')--}}
{{--    <script>--}}
{{--        $('.custom-btn').click (function (){--}}
{{--            var jobInstruction = $(this).data('instruction')--}}

{{--            $('.instruction-text').attr(jobInstruction);--}}
{{--        })--}}
{{--    </script>--}}
{{--@endsection--}}
