@extends('layouts.main')

@section('title', __('translates.navbar.commands'))
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
            MB-P-023/04 Əmrlərin Qeydiyyatı Jurnalı
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('commands.index')}}">
        <div class="col-12 col-md-6 mb-3">
            <div class="input-group mb-3">
                <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                    <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('commands.index')}}"><i class="fal fa-times"></i></a>
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
                <a class="btn btn-outline-success float-right" href="{{route('commands.create')}}">@lang('translates.buttons.create')</a>
            </div>

            <div class="col-12 m-3">
                <a class="btn btn-outline-success float-left" href="{{ route('commands.index') }}">Mobil Broker</a>
            </div>
            <div class="col-12 m-3">
                <form action="{{ route('commands.index') }}" id="logisticsFilter">
                    <input type="hidden" name="company_id" value="2">
                    <button class="btn btn-outline-success float-left" for="logisticsFilter" type="submit">Mobil Logistics</button>
                </form>
            </div>

            <table class="table table-responsive-sm table-hover" id="command">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Əmrin nömrəsi</th>
                    <th scope="col">Əmrin tarixi</th>
                    <th scope="col">Əmrin məzmunu</th>
                    <th scope="col">İşçinin adı, soyadı ata adı</th>
                    <th scope="col">İcra edən</th>
                    <th scope="col">Təsdiqlədi</th>
                    <th scope="col">@lang('translates.parameters.types.operation')</th>
                </tr>
                </thead>
                <tbody id="sortableCommand">
                @forelse($commands as $command)
                     <tr id="item-{{$command->getAttribute('id')}}">
                         <td @if(request()->get('limit') == 'all') class="sortable" @endif>{{$command->getAttribute('ordering') + 1}}</td>
                        <td>{{$command->getAttribute('number')}}</td>
                        <td>{{optional($command->getAttribute('command_date'))->format('Y-m-d')}}</td>
                        <td>{{$command->getAttribute('content')}}</td>
                         <td style="word-break: break-word; ">
                         @foreach($command->users as $user)
                            {{$user->getAttribute('fullname')}}@if(!$loop->last) ,  @endif
                         @endforeach
                         </td>
                        <td>{{$command->getRelationValue('executors')->getFullnameWithPositionAttribute()}}</td>
                        <td>{{$command->getRelationValue('confirmings')->getFullnameWithPositionAttribute()}}</td>
                         <td>
                             <div class="btn-sm-group">
                                 <a href="{{route('commands.create', ['id' => $command])}}" class="btn btn-sm btn-outline-primary">
                                     <i class="fal fa-copy"></i>
                                 </a>
                                 <a href="{{route('commands.show', $command)}}" class="btn btn-sm btn-outline-primary">
                                     <i class="fal fa-eye"></i>
                                 </a>
                                 <a href="{{route('commands.edit', $command)}}" class="btn btn-sm btn-outline-success">
                                     <i class="fal fa-pen"></i>
                                 </a>
                                 <a href="{{route('commands.destroy', $command)}}" delete data-name="{{$command->getAttribute('id')}}" class="btn btn-sm btn-outline-danger">
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
                    {{$commands->appends(request()->input())->links()}}
                </div>
            @endif

        </div>
@endsection
@section('scripts')
    @if(auth()->user()->hasPermission('update-user') && is_null(request()->get('status')))
        <script>
            $(function () {
                $('#sortableCommand').sortable({
                    axis: 'y',
                    handle: ".sortable",
                    update: function () {
                        var data = $(this).sortable('serialize');
                        $.ajax({
                            type: "POST",
                            data: data,
                            url: "{{route('commands.sortable')}}",
                        });
                    }
                });
                $('#sortableCommand').disableSelection();
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
            $('#command').DataTable();
        });
    </script>
@endsection
