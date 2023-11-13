@extends('layouts.main')

@section('title', trans('translates.navbar.salary'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.salary')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('salaries.index')}}">
        <div class="row d-flex justify-content-between mb-2">

            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control"
                           placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.name')])"
                           aria-label="Recipient's clientname" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('salaries.index')}}"><i
                                    class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-8 col-md-3  mb-3">
                <select name="limit" class="custom-select">
                    @foreach([25, 50, 100] as $size)
                        <option @if(request()->get('limit') == $size) selected
                                @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            @can('create', App\Models\Salary::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right"
                       href="{{route('salaries.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">VOEN</th>
                        <th scope="col">@lang('translates.columns.phone')</th>
                        <th scope="col">@lang('translates.columns.email')</th>
                        <th scope="col">@lang('translates.fields.note')</th>
                        <th scope="col">Ümumi Qiymət Ortalama(ortalama qiymət)</th>
                        <th scope="col">Qiymətləndirmənin nəticəsi</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($salaries as $salary)
                        @php
                        if ($salary->getAttribute('is_service') == 0){
                            $total = 5;
                        }
                        else
                            $total = 10;
                        @endphp
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$salary->getAttribute('name')}}</td>
                            <td>{{$salary->getAttribute('voen')}}</td>
                            <td>{{$salary->getAttribute('phone')}}</td>
                            <td>{{$salary->getAttribute('email')}}</td>
                            <td>{{$salary->getAttribute('note')}}</td>
                            <td>

                                {{ $math =
                                    ($salary->getAttribute('quality') +
                                    $salary->getAttribute('delivery') +
                                    $salary->getAttribute('distributor') +
                                    $salary->getAttribute('availability') +
                                    $salary->getAttribute('certificate') +
                                    $salary->getAttribute('support') +
                                    $salary->getAttribute('price') +
                                    $salary->getAttribute('payment') +
                                    $salary->getAttribute('returning') +
                                    $salary->getAttribute('replacement')) / $total
                                }}
                            </td>
                            <td>
                                {{$math}}%
                            </td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $salary)
                                        <a href="{{route('salaries.show', $salary)}}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $salary)
                                        <a href="{{route('salaries.edit', $salary)}}"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $salary)
                                        <a href="{{route('salaries.destroy', $salary)}}" delete
                                           data-name="{{$salary->getAttribute('name')}}"
                                           class="btn btn-sm btn-outline-danger">
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
                                    <div class="col-7 alert alert-danger text-center"
                                         role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$salaries->appends(request()->input())->links()}}
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