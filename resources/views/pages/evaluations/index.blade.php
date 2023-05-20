@extends('layouts.main')

@section('title', trans('translates.navbar.evaluation'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.evaluation')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('evaluations.index')}}">
        <div class="row d-flex justify-content-between mb-2">

            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.name')])" aria-label="Recipient's clientname" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('evaluations.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-8 col-md-3  mb-3">
                <select name="limit" class="custom-select">
                    @foreach([25, 50, 100] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            @can('create', App\Models\Evaluation::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('evaluations.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.quality')</th>
                        <th scope="col">@lang('translates.columns.delivery')</th>
                        <th scope="col">@lang('translates.columns.distributor')</th>
                        <th scope="col">@lang('translates.columns.availability')</th>
                        <th scope="col">@lang('translates.columns.certificate')</th>
                        <th scope="col">@lang('translates.columns.support')</th>
                        <th scope="col">Qiymət</th>
                        <th scope="col">Ödəmə şərtləri</th>
                        <th scope="col">@lang('translates.columns.returning')</th>
                        <th scope="col">@lang('translates.columns.replacement')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($evaluations as $evaluation)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$evaluation->getAttribute('quality')}}</td>
                            <td>{{$evaluation->getAttribute('delivery')}}</td>
                            <td>{{$evaluation->getAttribute('distributor')}}</td>
                            <td>{{$evaluation->getAttribute('availability')}}</td>
                            <td>{{$evaluation->getAttribute('certificate')}}</td>
                            <td>{{$evaluation->getAttribute('support')}}</td>
                            <td>{{$evaluation->getAttribute('price')}}</td>
                            <td>{{$evaluation->getAttribute('payment')}}</td>
                            <td>{{$evaluation->getAttribute('returning')}}</td>
                            <td>{{$evaluation->getAttribute('replacement')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $evaluation)
                                        <a href="{{route('evaluations.show', $evaluation)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $evaluation)
                                        <a href="{{route('evaluations.edit', $evaluation)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $evaluation)
                                        <a href="{{route('evaluations.destroy', $evaluation)}}" delete data-name="{{$evaluation->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="7">
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
                    {{$evaluations->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        $('select').change(function () {
            this.form.submit();
        });
    </script>
@endsection