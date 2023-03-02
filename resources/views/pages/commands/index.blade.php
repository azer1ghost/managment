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
            @lang('translates.navbar.commands')
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                @can('create', \App\Models\Command::class)
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('commands.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                <table class="table table-responsive-sm table-hover">
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
                    <tbody>
                    @forelse($commands as $command)
                         <tr>

                            <td>{{$loop->iteration}}</td>
                            <td>{{$command->getAttribute('number')}}</td>
                            <td>{{$command->getAttribute('command_date')}}</td>
                            <td>{{$command->getAttribute('content')}}</td>
                             <td style="word-break: break-word; ">
                             @foreach($command->users as $user)
                                {{$user->getAttribute('fullname')}}@if(!$loop->last) ,  @endif
                             @endforeach
                             </td>
                            <td>{{$command->getRelationValue('executors')->getFullnameWithPositionAttribute()}}</td>
                            <td>{{$command->getRelationValue('confirmings')->getFullnameWithPositionAttribute()}}</td>
                             @can('update', App\Models\Command::class)
                                 <td>
                                     <div class="btn-sm-group">
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
                             @endcan
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
                    <div class="float-right">
                        {{$commands->appends(request()->input())->links()}}
                    </div>
            </div>
@endsection
@section('scripts')
    <script>

        confirmJs($("a[accept]"));

        function confirmJs(el){
            el.click(function(e){
                const name = $(this).data('name') ?? 'Pending records'
                const url = $(this).attr('href')
                const checkedWorks = [];

                $("input[name='works[]']:checked").each(function(){
                    checkedWorks.push($(this).val());
                });

                e.preventDefault()

                $.confirm({
                    title: 'Confirm verification',
                    content: `Are you sure to verify <b>${name}</b> ?`,
                    autoClose: 'confirm|8000',
                    icon: 'fa fa-question',
                    type: 'blue',
                    theme: 'modern',
                    typeAnimated: true,
                    buttons: {
                        confirm: function () {
                            $.ajax({
                                url: url,
                                type: 'PUT',
                                data: {'registrationLogs': checkedWorks},
                                success: function (responseObject, textStatus, xhr)
                                {
                                    $.confirm({
                                        title: 'Verification successful',
                                        icon: 'fa fa-check',
                                        content: '<b>:name</b>'.replace(':name',  name),
                                        type: 'blue',
                                        typeAnimated: true,
                                        autoClose: 'reload|3000',
                                        theme: 'modern',
                                        buttons: {
                                            reload: {
                                                text: 'Ok',
                                                btnClass: 'btn-blue',
                                                keys: ['enter'],
                                                action: function(){
                                                    window.location.reload()
                                                }
                                            }
                                        }
                                    });
                                },
                                error: function (err)
                                {
                                    console.log(err);
                                    $.confirm({
                                        title: 'Ops something went wrong!',
                                        content: err?.responseJSON,
                                        type: 'red',
                                        typeAnimated: true,
                                        buttons: {
                                            close: {
                                                text: 'Close',
                                                btnClass: 'btn-blue',
                                                keys: ['enter'],
                                            }
                                        }
                                    });
                                }
                            });
                        },
                        cancel: function () {},
                    }
                });
            });
        }
    </script>

@endsection