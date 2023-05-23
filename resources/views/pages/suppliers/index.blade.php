@extends('layouts.main')

@section('title', trans('translates.navbar.supplier'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.supplier')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('suppliers.index')}}">
        <div class="row d-flex justify-content-between mb-2">

            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control"
                           placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.name')])"
                           aria-label="Recipient's clientname" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('suppliers.index')}}"><i
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
            @can('create', App\Models\Supplier::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right"
                       href="{{route('suppliers.create')}}">@lang('translates.buttons.create')</a>
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
                    @forelse($suppliers as $supplier)
                        @php
                        if ($supplier->getAttribute('is_service') == 0){
                            $total = 5;
                        }
                        else
                            $total = 10;
                        @endphp
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$supplier->getAttribute('name')}}</td>
                            <td>{{$supplier->getAttribute('voen')}}</td>
                            <td>{{$supplier->getAttribute('phone')}}</td>
                            <td>{{$supplier->getAttribute('email')}}</td>
                            <td>{{$supplier->getAttribute('note')}}</td>
                            <td>

                                {{ $math =
                                    ($supplier->getAttribute('quality') +
                                    $supplier->getAttribute('delivery') +
                                    $supplier->getAttribute('distributor') +
                                    $supplier->getAttribute('availability') +
                                    $supplier->getAttribute('certificate') +
                                    $supplier->getAttribute('support') +
                                    $supplier->getAttribute('price') +
                                    $supplier->getAttribute('payment') +
                                    $supplier->getAttribute('returning') +
                                    $supplier->getAttribute('replacement')) / $total
                                }}
                            </td>
                            <td>
                                {{$math}}%
                            </td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $supplier)
                                        <a href="{{route('suppliers.show', $supplier)}}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $supplier)
                                        <a href="{{route('suppliers.edit', $supplier)}}"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $supplier)
                                        <a href="{{route('suppliers.destroy', $supplier)}}" delete
                                           data-name="{{$supplier->getAttribute('name')}}"
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
                    {{$suppliers->appends(request()->input())->links()}}
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