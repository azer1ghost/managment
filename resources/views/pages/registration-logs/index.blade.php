@extends('layouts.main')

@section('title', __('translates.navbar.registration_logs'))
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
            MB-P-023/02 Daxil Olan Sənədlərin Qeydiyyatı Jurnalı
        </x-bread-crumb-link>
    </x-bread-crumb>
        <div class="col-12">
            @can('create', \App\Models\RegistrationLog::class)
                <div class="col-12">
                    <a class="btn btn-outline-success float-right" href="{{route('registration-logs.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
                <div class="col-12">
                    <a class="btn btn-outline-success float-left" href="{{ route('registration-logs.index') }}">Mobil Broker</a>
                </div>
                <div class="col-12">
                    <form action="{{ route('registration-logs.index') }}">
                        <input type="hidden" name="company_id" value="2">
                        <button class="btn btn-outline-success float-left" type="submit">Mobil Logistics</button>
                    </form>
                </div>
            <table id="regLog" class="table table-responsive-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('translates.columns.company')</th>
                    <th scope="col">Sənədin daxilolma tarixi</th>
                    <th scope="col">Sənədi göndərən</th>
                    <th scope="col">Sənədin nömrəsi</th>
                    <th scope="col">Sənədin qısa məzmunu</th>
                    <th scope="col">Sənəd</th>
                    <th scope="col">Dərkənar</th>
                    <th scope="col">İcraçı</th>
                    <th scope="col">Alınma barədə tarix</th>
                    <th scope="col">@lang('translates.parameters.types.operation')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($registrationLogs as $registrationLog)
                     <tr>

                        <td>{{$loop->iteration}}</td>
                        <td>{{$registrationLog->getRelationValue('companies')->getAttribute('name')}}</td>
                        <td>{{$registrationLog->getAttribute('arrived_at')}}</td>
                        <td>{{$registrationLog->getAttribute('sender')}}</td>
                        <td>{{$registrationLog->getAttribute('number')}}</td>
                        <td>{{$registrationLog->getAttribute('description')}}</td>
                         <td> @php($supportedTypes = \App\Models\Document::supportedTypeIcons())
                             @foreach($registrationLog->documents as $document)
                                 @php($type = $supportedTypes[$document->type])
                                 @php($route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document))
                                 <a href="{{$route}}" data-toggle="tooltip" title="{{$document->file}}" target="_blank" class="text-dark d-flex align-items-center mr-2" style=" word-break: break-word">
                                     <i class="fa fa-file-{{$type['icon']}} fa-2x m-1 text-{{$type['color']}}"></i>
                                     <span>{{substr($document->name, 0, 10) . '...'}} </span>
                                 </a>
                             @endforeach</td>
                        <td>{{$registrationLog->getRelationValue('performers')->getFullnameWithPositionAttribute()}}</td>
                        <td>{{$registrationLog->getRelationValue('receivers')->getFullnameWithPositionAttribute()}}</td>
                        <td>{{$registrationLog->getAttribute('received_at')}}</td>
                         @can('update', App\Models\RegistrationLog::class)
                             <td>
                                 <div class="btn-sm-group">
                                     <a href="{{route('registration-logs.show', $registrationLog)}}" class="btn btn-sm btn-outline-primary">
                                         <i class="fal fa-eye"></i>
                                     </a>
                                     <a href="{{route('registration-logs.edit', $registrationLog)}}" class="btn btn-sm btn-outline-success">
                                         <i class="fal fa-pen"></i>
                                     </a>
                                     <a href="{{route('registration-logs.destroy', $registrationLog)}}" delete data-name="{{$registrationLog->getAttribute('id')}}" class="btn btn-sm btn-outline-danger">
                                         <i class="fal fa-trash"></i>
                                     </a>
                                     <a href="{{route('registration-logs.accepted', $registrationLog)}}" accept data-name="{{$registrationLog->getAttribute('id')}}" class="btn btn-sm btn-outline-primary">
                                         <i class="fal fa-check text-success"></i>
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
                {{$registrationLogs->appends(request()->input())->links()}}
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
    <script>
        $(document).ready(function () {
            $('#regLog').DataTable();
        });
    </script>

@endsection