@extends('layouts.main')

@section('title', trans('translates.navbar.job_instruction'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.job_instruction')
        </x-bread-crumb-link>
    </x-bread-crumb>
        <div class="row d-flex justify-content-between mb-2">

            @can('create', App\Models\JobInstruction::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('job-instructions.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.user')</th>
                        <th scope="col">@lang('translates.fields.file')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($jobInstructions as $jobInstruction)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$jobInstruction->getRelationValue('users')->getFullnameWithPositionAttribute()}}</td>
                            @foreach($jobInstruction->documents as $document)
                                @php
                                    $route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document)
                                @endphp
                                <td>
                                    <a href="{{$route}}" data-toggle="tooltip" title="{{$document->file}}" target="_blank" class="text-dark d-flex align-items-center mr-2" style="font-size: 20px; word-break: break-word">
                                        <i style="font-size: 70px" class="fa fa-file-pdf fa-3x mr-2"></i>
                                        <span>{{$document->name}}</span>
                                    </a>
                                </td>
                            @endforeach
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $jobInstruction)
                                        <a href="{{route('job-instructions.show', $jobInstruction)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $jobInstruction)
                                        <a href="{{route('job-instructions.edit', $jobInstruction)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $jobInstruction)
                                        <a href="{{route('job-instructions.destroy', $jobInstruction)}}" delete data-name="{{$jobInstruction->getRelationValue('users')->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
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
            <div class="col-6">
                <div class="float-right">
                    {{$jobInstructions->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection