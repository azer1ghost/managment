@extends('layouts.main')

@section('title', __('translates.navbar.parameter'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.parameter')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('parameters.index')}}">
        <div class="row d-flex justify-content-between mb-2">
           <div class="col-6">
               <div class="input-group mb-3">
                   <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                   <div class="input-group-append">
                       <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                       <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('parameters.index')}}"><i class="fal fa-times"></i></a>
                   </div>
               </div>
           </div>
            @can('create', App\Models\Parameter::class)
                <div class="col-4">
                    <a class="btn btn-outline-success float-right" href="{{route('parameters.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.parameter_label')</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.columns.type')</th>
                        <th scope="col">@lang('translates.columns.order')</th>
                        <th scope="col">@lang('translates.columns.parent_option')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($parameters as $parameter)
                        <tr>
                            <th scope="row">{{$parameter->getAttribute('id')}}</th>
                            <td>{{$parameter->getAttribute('label')}}</td>
                            <td>{{$parameter->getAttribute('name')}}</td>
                            <td>{{$parameter->getAttribute('type')}}</td>
                            <td>{{$parameter->getAttribute('order')}}</td>
                            <td>{{optional($parameter->getRelationValue('option'))->getAttribute('text') ?? 'Null'}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $parameter)
                                        <a href="{{route('parameters.show', $parameter)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $parameter)
                                        <a href="{{route('parameters.edit', $parameter)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $parameter)
                                        <a href="{{route('parameters.destroy', $parameter)}}" delete data-name="{{$parameter->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
            </div>
            <div class="col-2">
                <select name="limit" class="custom-select" id="size">
                    @foreach([10, 50, 100] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2">
                <select name="type" class="custom-select" id="type">
                    <option selected value="">Type</option>
                    @foreach($types as $key => $type)
                        <option @if(request()->get('type') == $key) selected @endif value="{{$key}}">{{$type}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$parameters->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('select').change(function() {
                this.form.submit();
            });
        });
    </script>
@endsection