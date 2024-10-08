@extends('layouts.main')

@section('title', __('translates.navbar.access_rate'))
@section('style')
    <style>
        table {
            table-layout: fixed;
            width: 100%;
        }

        td, th {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            MB-P-023/06 @lang('translates.navbar.access_rate')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="col-12">
        @can('create', App\Models\AccessRate::class)
            <div class="col-12">
                <a class="btn btn-outline-success float-right mb-3"
                   href="{{route('access-rates.create')}}">@lang('translates.buttons.create')</a>
            </div>
        @endcan
        @can('create', App\Models\AccessRate::class)
            <div class="col-12">
                <a class="btn btn-outline-primary float-left"
                   href="{{route('folders.index')}}">@lang('translates.navbar.folder')</a>
            </div>
        @endcan
        <a class="btn btn-outline-success float-left" href="{{ route('access-rates.index') }}">Mobil Broker</a>

        <form action="{{ route('access-rates.index') }}">
            <input type="hidden" name="company_id" value="2">
            <button class="btn btn-outline-success float-left" type="submit">Mobil Logistics</button>
        </form>

        <table class="table table-responsive-sm table-hover">
            <tr>
                <th scope="col">Qovluq Adı</th>
                <th scope="col">Tərkibi</th>
                <th scope="col">Oxuya Bilər</th>
                <th scope="col">Dəyişiklik edə bilər</th>
                <th scope="col">Çap edə bilər</th>
                <th scope="col">Vəzifə</th>
                <th scope="col">Əməliyyatlar</th>
            </tr>
            <tr>
                @foreach($folders as $folder)
                    <th rowspan="{{$folder->accessRates->count()}}">{{$folder->getAttribute('name')}}</th>
                    <td rowspan="{{$folder->accessRates->count()}}">{{$folder->getAttribute('composition')}}</td>
                    @foreach($folder->accessRates as $accessRate)

                        <td>@if($accessRate->getAttribute('is_readonly') == 1)
                                +
                            @else
                                -
                            @endif</td>
                        <td>@if($accessRate->getAttribute('is_change') == 1)
                                +
                            @else
                                -
                            @endif</td>
                        <td>@if($accessRate->getAttribute('is_print') == 1)
                                +
                            @else
                                -
                            @endif</td>
                        {{--                            <td>{{$accessRate->getRelationValue('positions')->getAttribute('name')}}</td>--}}
                        <td style="word-break: break-word; ">
                            @foreach($accessRate->positions as $position)
                                {{$position->getAttribute('name')}}@if(!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                        @can('update', App\Models\Folder::class)
                            <td>
                                <div class="btn-sm-group">
                                    <a href="{{route('access-rates.show', $accessRate)}}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fal fa-eye"></i>
                                    </a>
                                    <a href="{{route('access-rates.edit', $accessRate)}}"
                                       class="btn btn-sm btn-outline-success">
                                        <i class="fal fa-pen"></i>
                                    </a>
                                    <a href="{{route('access-rates.destroy', $accessRate)}}" delete
                                       data-name="{{$accessRate->getAttribute('id')}}"
                                       class="btn btn-sm btn-outline-danger">
                                        <i class="fal fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        @endcan
                </tr>
              @endforeach
            @endforeach
        </table>
    </div>
@endsection
