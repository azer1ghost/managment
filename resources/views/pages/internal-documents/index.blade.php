@extends('layouts.main')

@section('title', __('translates.navbar.internal_document'))
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
            MB-P-023/01 Daxili Sənədlərin Siyahısı
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                @can('create', App\Models\InternalDocument::class)
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('internal-documents.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                <div class="col-12 m-3">
                    <a class="btn btn-outline-success float-left" href="{{ route('internal-documents.index') }}">Mobil Broker</a>
                </div>
                <div class="col-12 m-3">
                    <form action="{{ route('internal-documents.index') }}">
                        <input type="hidden" name="company_id" value="2">
                        <button class="btn btn-outline-success float-left" type="submit">Mobil Logistics</button>
                    </form>
                </div>
                <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Şöbə</th>
                        <th scope="col">Sənəd Kodu</th>
                        <th scope="col">Adı</th>
                        <th scope="col">Əməliyyatlar</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @forelse($internalDocuments as $internalDocument)
                            <tr id="item-{{$internalDocument->getAttribute('id')}}">
                            <th>{{$loop->iteration}}</th>
                            <th class="sortable">{{$internalDocument->getRelationValue('departments')->getAttribute('name')}}</th>
                            <td>{{$internalDocument->getAttribute('document_code')}}</td>
                            <td>{{$internalDocument->getAttribute('document_name')}}</td>
                            @can('update', App\Models\InternalDocument::class)
                                <td>
                                    <div class="btn-sm-group">
                                            <a href="{{route('internal-documents.show', $internalDocument)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                            <a href="{{route('internal-documents.edit', $internalDocument)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                            <a href="{{route('internal-documents.destroy', $internalDocument)}}" delete data-name="{{$internalDocument->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
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
                </div>
            </div>
@endsection
@section('scripts')
        <script>
            $(function () {
                $('#sortable').sortable({
                    axis: 'y',
                    handle: ".sortable",
                    update: function () {
                        var data = $(this).sortable('serialize');
                        $.ajax({
                            type: "POST",
                            data: data,
                            url: "{{route('internal-document.sortable')}}",
                        });
                    }
                });
                $('#sortable').disableSelection();
            });
        </script>
@endsection
