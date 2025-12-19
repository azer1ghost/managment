@extends('layouts.main')

@section('title', trans('translates.navbar.job_instruction'))

@section('content')
    <div class="col-12">
        @can('create', App\Models\JobInstruction::class)
            <div class="col-12">
                <a class="btn btn-outline-success float-right" href="{{route('job-instructions.create')}}">@lang('translates.buttons.create')</a>
            </div>
        @endcan
        <div class="table-responsive" style="overflow-x: auto;">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">@lang('translates.columns.name')</th>
                <th scope="col">@lang('translates.columns.actions')</th>
            </tr>
            </thead>
            <tbody id="sortable">
            @forelse($jobInstructions as $jobInstruction)
                <th>{{$jobInstruction->getRelationValue('users')->getFullnameWithPositionAttribute()}}</th>
                    @can('update', App\Models\JobInstruction::class)
                        <td>
                            <div class="btn-sm-group">
                                <a href="{{route('job-instructions.show', $jobInstruction)}}" class="btn btn-sm btn-outline-primary">
                                    <i class="fal fa-eye"></i>
                                </a>
                                <a href="{{route('job-instructions.edit', $jobInstruction)}}" class="btn btn-sm btn-outline-success">
                                    <i class="fal fa-pen"></i>
                                </a>
                                <a href="{{route('job-instructions.destroy', $jobInstruction)}}" delete data-name="{{$jobInstruction->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
                                    <i class="fal fa-trash"></i>
                                </a>
                            </div>
                        </td>
                            @endcan
                        </tr>
                        @empty
                <tr>
                    <th colspan="20">
                        <div class="row justify-content-center m-3">
                            <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                        </div>
                    </th>
                </tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
@endsection
