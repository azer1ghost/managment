@extends('layouts.main')

@section('title', trans('translates.navbar.job_instruction'))

@section('content')
    <style>
        h2 {
            background: #f5f7ff;
            border: 1px #F5F6FC solid;
            text-align: center;
            padding: 5px;
            color: #000000;
            cursor: pointer;
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
    <div class="row mb-2">
        <div class="col-12 justify-content-center">
            @foreach($jobInstructions as $jobInstruction)
                <h2 data-toggle="collapse" href="#instruction-{{$loop->iteration}}">{{$jobInstruction->getRelationValue('users')->getFullnameWithPositionAttribute()}}
                    <div class="btn-sm-group">
                        @can('update', App\Models\JobInstruction::class)
                            <a href="{{route('job-instructions.edit', $jobInstruction)}}" class="btn btn-sm btn-outline-success">
                                <i class="fal fa-pen"></i>
                            </a>
                            <a href="{{route('job-instructions.destroy', $jobInstruction)}}" delete data-name="{{$jobInstruction->getRelationValue('users')->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                <i class="fal fa-trash"></i>
                            </a>
                        @endcan
                    </div>
                </h2>

                <div class="collapse" id="instruction-{{$loop->iteration}}">
                    <p > {!! $jobInstruction->getAttribute('instruction') !!}</p>
                </div>
            @endforeach
        </div>

        <div class="col-6">
            <div class="float-right">
                {{$jobInstructions->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
    @can('create', App\Models\JobInstruction::class)
        <div class="col-12">
            <a class="btn btn-outline-success float-right"
               href="{{route('job-instructions.create')}}">@lang('translates.buttons.create')</a>
        </div>
    @endcan
@endsection