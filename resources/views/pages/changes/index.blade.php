@extends('layouts.main')

@section('title', __('translates.navbar.changes'))
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
            MB-P-012/01 Dəyişikliklərin Qeydiyyat Cədvəli
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                @can('create', \App\Models\Change::class)
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('changes.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                <table class="table table-responsive-sm table-hover" id="changes">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Dəyişikliyin tarixi</th>
                        <th scope="col">Dəyişikliyin sahibi</th>
                        <th scope="col">Aid olduğu proses/şöbə</th>
                        <th scope="col">Dəyişikliyin təsviri</th>
                        <th scope="col">Dəyişikliyin səbəbi</th>
                        <th scope="col">Təsiri</th>
                        <th scope="col">Istinad edilən sənəd</th>
                        <th scope="col">Dəyişikliyin təhlilinə və tətbiq edilməsinə məsul bölmə/şəxs</th>
                        <th scope="col">Dəyişikliyin effektivliyi</th>
                        <th scope="col">Qeydlər</th>
                        <th scope="col">@lang('translates.parameters.types.operation')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($changes as $change)
                         <tr>

                            <td>{{$loop->iteration}}</td>
                            <td>{{optional($change->getAttribute('datetime'))->format('Y-m-d')}}</td>
                            <td>{{$change->getRelationValue('users')->getAttribute('fullname')}}</td>
                            <td>{{$change->getRelationValue('departments')->getAttribute('name') ?? trans('translates.general.all_departments')}}</td>
                            <td>{{$change->getAttribute('description')}}</td>
                            <td>{{$change->getAttribute('reason')}}</td>
                             <td>{{$change->getAttribute('result')}}</td>
                             <td> @php($supportedTypes = \App\Models\Document::supportedTypeIcons())
                                @foreach($change->documents as $document)
                                    @php($type = $supportedTypes[$document->type])
                                    @php($route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document))
                                    <a href="{{$route}}" data-toggle="tooltip" title="{{$document->file}}" target="_blank" class="text-dark d-flex align-items-center mr-2" style=" word-break: break-word">
                                        <i class="fa fa-file-{{$type['icon']}} fa-2x m-1 text-{{$type['color']}}"></i>
                                        <span>{{substr($document->name, 0, 10) . '...'}} </span>
                                    </a>
                                @endforeach</td>
                            <td>{{$change->getRelationValue('responsibles')->getAttribute('fullname')}}</td>
                            <td>@lang('translates.effectivity.'.$change->getAttribute('effectivity'))</td>
                            <td>{{$change->getAttribute('note')}}</td>
                            @can('update', App\Models\Change::class)
                                <td>
                                    <div class="btn-sm-group">
                                            <a href="{{route('changes.show', $change)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                            <a href="{{route('changes.edit', $change)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                            <a href="{{route('changes.destroy', $change)}}" delete data-name="{{$change->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
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
                        {{$changes->appends(request()->input())->links()}}
                    </div>
            </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#changes').DataTable();
        });
    </script>
@endsection