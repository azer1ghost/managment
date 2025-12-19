@extends('layouts.main')

@section('title', __('translates.navbar.fund'))
@section('style')
    <style>
        table {
            table-layout:fixed;
            width:100%;
        }
        td, th {
            text-align: center;
            margin: 50px;
        }
    </style>
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            Bank Və Kodlar
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('funds.index')}}">
        <div class="col-12 col-md-6 mb-3">
            <div class="input-group mb-3">
                <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                    <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('funds.index')}}"><i class="fal fa-times"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <select name="limit" class="custom-select">
                @foreach([25, 50, 100, 'all'] as $size)
                    <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="col-12">
        <div class="col-12">
            <a class="btn btn-outline-success float-right" href="{{route('funds.create')}}">@lang('translates.buttons.create')</a>
        </div>

        <div class="table-responsive" style="overflow-x: auto;">
        <table class="table table-hover" id="command">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Şirkət</th>
                <th scope="col">VÖEN</th>
                <th scope="col">ƏSAS FƏALİYYƏT KODU</th>
                <th scope="col">DIREKTOR</th>
                <th scope="col">ASAN İMZA</th>
                <th scope="col">KOD </th>
                <th scope="col">Qeydiyyat ünvanı</th>
                <th scope="col">VERGİ İST. KOD.</th>
                <th scope="col">PAROL</th>
                <th scope="col">ŞİFRƏ</th>
                <th scope="col">BANK RESPUBLIKA ID</th>
                <th scope="col">BANK RESPUBLIKA KOD</th>
                <th scope="col">KAPİTAL BANK ID</th>
                <th scope="col">KAPİTAL BANK KOD</th>
                <th scope="col">@lang('translates.parameters.types.operation')</th>
            </tr>
            </thead>
            <tbody>
            @forelse($funds as $fund)
                <tr id="item-{{$fund->getAttribute('id')}}">
                    <th>{{$loop->iteration}}</th>
                    <td>{{$fund->getRelationValue('companies')->getAttribute('name')}}</td>
                    <td>{{$fund->getAttribute('voen')}}</td>
                    <td>{{$fund->getAttribute('main_activity')}}</td>
                    <td>{{$fund->getRelationValue('users')->getAttribute('fullname')}}</td>
                    <td>{{$fund->getAttribute('asan_imza')}}</td>
                    <td>{{$fund->getAttribute('code')}}</td>
                    <td>{{$fund->getAttribute('adress')}}</td>
                    <td>{{$fund->getAttribute('voen_code')}}</td>
                    <td>{{$fund->getAttribute('voen_pass')}}</td>
                    <td>{{$fund->getAttribute('pass')}}</td>
                    <td>{{$fund->getAttribute('respublika_code')}}</td>
                    <td>{{$fund->getAttribute('respublika_pass')}}</td>
                    <td>{{$fund->getAttribute('kapital_code')}}</td>
                    <td>{{$fund->getAttribute('kapital_pass')}}</td>

                    <td>
                        <div class="btn-sm-group">
                            <a href="{{route('funds.show', $fund)}}" class="btn btn-sm btn-outline-primary">
                                <i class="fal fa-eye"></i>
                            </a>
                            <a href="{{route('funds.edit', $fund)}}" class="btn btn-sm btn-outline-success">
                                <i class="fal fa-pen"></i>
                            </a>
                            <a href="{{route('funds.destroy', $fund)}}" delete data-name="{{$fund->getAttribute('id')}}" class="btn btn-sm btn-outline-danger">
                                <i class="fal fa-trash"></i>
                            </a>
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
        @if(request()->get('limit') !== 'all')
            <div class="float-right">
                {{$funds->appends(request()->input())->links()}}
            </div>
        @endif

    </div>
@endsection
@section('scripts')
@endsection
