@extends('layouts.main')

@section('title', __('translates.navbar.option'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.option')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('options.index')}}">
        <div class="row d-flex justify-content-between mb-2">
           <div class="col-6">
               <div class="input-group mb-3">
                   <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's optionname" aria-describedby="basic-addon2">
                   <div class="input-group-append">
                       <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                       <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('options.index')}}"><i class="fal fa-times"></i></a>
                   </div>
               </div>
           </div>
            @can('create', App\Models\Option::class)
                <div class="col-4">
                    <a class="btn btn-outline-success float-right" href="{{route('options.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Text</th>
                        <th scope="col">Parameters</th>
                        <th scope="col">Departments</th>
                        <th scope="col">Companies</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($options as $option)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$option->getAttribute('text')}}</td>
                            <td>{{implode(',', array_unique($option->getRelationValue('parameters')->pluck('name')->map(fn($p) => str_title($p))->toArray()))}}</td>
                            <td>{{implode(',', array_unique($option->getRelationValue('departments')->pluck('name')->map(fn($p) => str_title($p))->toArray()))}}</td>
                            <td>{{implode(',', array_unique($option->getRelationValue('companies')->pluck('name')->map(fn($p) => str_title($p))->toArray()))}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $option)
                                        <a href="{{route('options.show', $option)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $option)
                                        <a href="{{route('options.edit', $option)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $option)
                                        <a href="{{route('options.destroy', $option)}}" delete data-name="{{$option->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
            <div class="col-3">
                <select name="type" class="custom-select" id="type">
                    <option selected value="">Type</option>
                    @foreach($names as $key => $type)
                        <option @if(request()->get('type') == $key) selected @endif value="{{$key}}">{{$type}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                <select name="company" class="custom-select" id="company">
                    <option selected value="">Company</option>
                    @foreach($companies as $key => $company)
                        <option @if(request()->get('company') == $key) selected @endif value="{{$key}}">{{$company}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <div class="float-right">
                    {{$options->appends(request()->input())->links()}}
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