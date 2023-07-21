@extends('layouts.main')

@section('title', __('translates.navbar.summits'))
@section('style')
    <style>
        table {
            table-layout:fixed;
            width:100%;
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
            Tədbirlərin Qeydiyyatı Jurnalı
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('summits.index')}}">
        <div class="col-12 col-md-6 mb-3">
            <div class="input-group mb-3">
                <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                    <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('summits.index')}}"><i class="fal fa-times"></i></a>
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
            <a class="btn btn-outline-success float-right" href="{{route('summits.create')}}">@lang('translates.buttons.create')</a>
        </div>


        <table class="table table-responsive-sm table-hover" id="command">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Klub</th>
                <th scope="col">Tədbir</th>
                <th scope="col">Format</th>
                <th scope="col">Tarix</th>
                <th scope="col">Yer</th>
                <th scope="col">İştirakçılar</th>
                <th scope="col">Dress code</th>
                <th scope="col">Status</th>
                <th scope="col">@lang('translates.parameters.types.operation')</th>
            </tr>
            </thead>
            <tbody id="sortableSummit">
            @forelse($summits as $summit)
                <tr id="item-{{$summit->getAttribute('id')}}">
                    <td @if(request()->get('limit') == 'all') class="sortable" @endif>{{$summit->getAttribute('ordering') + 1}}</td>
                    <td>{{$summit->getAttribute('club')}}</td>
                    <td>{{$summit->getAttribute('event')}}</td>
                    <td>{{$summit->getAttribute('format')}}</td>
                    <td>{{optional($summit->getAttribute('date'))->format('Y-m-d')}}</td>
                    <td>{{$summit->getAttribute('place')}}</td>
                    <td style="word-break: break-word; ">
                        @foreach($summit->users as $user)
                            {{$user->getAttribute('fullname')}}@if(!$loop->last) ,  @endif
                        @endforeach
                    </td>
                    <td>{{$summit->getAttribute('dresscode')}}</td>
                    <td>{{$summit->getAttribute('status')}}</td>
                    <td>
                        <div class="btn-sm-group">
                            <a href="{{route('summits.show', $summit)}}" class="btn btn-sm btn-outline-primary">
                                <i class="fal fa-eye"></i>
                            </a>
                            <a href="{{route('summits.edit', $summit)}}" class="btn btn-sm btn-outline-success">
                                <i class="fal fa-pen"></i>
                            </a>
                            <a href="{{route('summits.destroy', $summit)}}" delete data-name="{{$summit->getAttribute('id')}}" class="btn btn-sm btn-outline-danger">
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
        @if(request()->get('limit') !== 'all')
            <div class="float-right">
                {{$summits->appends(request()->input())->links()}}
            </div>
        @endif

    </div>
@endsection
@section('scripts')
    @if(auth()->user()->hasPermission('update-user') && is_null(request()->get('status')))
        <script>
            $(function () {
                $('#sortableSummit').sortable({
                    axis: 'y',
                    handle: ".sortable",
                    update: function () {
                        var data = $(this).sortable('serialize');
                        $.ajax({
                            type: "POST",
                            data: data,
                            url: "{{route('summits.sortable')}}",
                        });
                    }
                });
                $('#sortableSummit').disableSelection();
            });
        </script>
    @endif
    <script>
        $('select').change(function(){
            this.form.submit();
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#summit').DataTable();
        });
    </script>
@endsection
