@extends('layouts.main')

@section('title', __('translates.navbar.protocols'))
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
            MB-P-023/05 Protokolların Qeydiyyatı Jurnalı
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                @can('create', \App\Models\Protocol::class)
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('protocols.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                    <div class="col-12 m-3">
                        <a class="btn btn-outline-success float-left" href="{{ route('protocols.index') }}">Mobil Broker</a>
                    </div>
                    <div class="col-12 m-3">
                        <form action="{{ route('protocols.index') }}">
                            <input type="hidden" name="company_id" value="2">
                            <button class="btn btn-outline-success float-left" type="submit">Mobil Logistics</button>
                        </form>
                    </div>
                <table id="example" class="table is-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Protokolun nömrəsi</th>
                        <th scope="col">Tarixi</th>
                        <th scope="col">Protokolun məzmunu</th>
                        <th scope="col">Protokol</th>
                        <th scope="col">Kim İmzalamışdır</th>
                        <th scope="col">İcraçı</th>
                        <th scope="col">@lang('translates.parameters.types.operation')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($protocols as $protocol)
                         <tr>

                            <td>{{$loop->iteration}}</td>
                            <td>{{$protocol->getAttribute('protocol_no')}}</td>
                             <td>{{optional($protocol->getAttribute('date'))->format('Y-m-d')}}</td>
                             <td>{{$protocol->getAttribute('content')}}</td>
                             <td> @php($supportedTypes = \App\Models\Document::supportedTypeIcons())
                                 @foreach($protocol->documents as $document)
                                     @php($type = $supportedTypes[$document->type])
                                     @php($route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document))
                                     <a href="{{$route}}" data-toggle="tooltip" title="{{$document->file}}" target="_blank" class="text-dark d-flex align-items-center mr-2" style=" word-break: break-word">
                                         <i class="fa fa-file-{{$type['icon']}} fa-2x m-1 text-{{$type['color']}}"></i>
                                         <span>{{substr($document->name, 0, 10) . '...'}} </span>
                                     </a>
                                 @endforeach</td>
                             <td>{{$protocol->getRelationValue('signatures')->getFullnameWithPositionAttribute()}}</td>
                             <td>{{$protocol->getRelationValue('performers')->getFullnameWithPositionAttribute()}}</td>
                         @can('update', App\Models\Protocol::class)
                                 <td>
                                     <div class="btn-sm-group">
                                         <a href="{{route('protocols.show', $protocol)}}" class="btn btn-sm btn-outline-primary">
                                             <i class="fal fa-eye"></i>
                                         </a>
                                         <a href="{{route('protocols.edit', $protocol)}}" class="btn btn-sm btn-outline-success">
                                             <i class="fal fa-pen"></i>
                                         </a>
                                         <a href="{{route('protocols.destroy', $protocol)}}" delete data-name="{{$protocol->getAttribute('id')}}" class="btn btn-sm btn-outline-danger">
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
                        {{$protocols->appends(request()->input())->links()}}
                    </div>
            </div>
@endsection
@section('scripts')

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>
@endsection