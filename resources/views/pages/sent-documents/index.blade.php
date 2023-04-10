@extends('layouts.main')

@section('title', __('translates.navbar.sent_document'))
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
            MB-P-023/03  @lang('translates.navbar.sent_document')
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                @can('create', App\Models\SentDocument::class)
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('sent-documents.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                    <div class="col-12 m-3">
                        <a class="btn btn-outline-success float-left" href="{{ route('sent-documents.index') }}">Mobil Broker</a>
                    </div>
                    <div class="col-12 m-3">
                        <form action="{{ route('sent-documents.index') }}">
                            <input type="hidden" name="company_id" value="2">
                            <button class="btn btn-outline-success float-left" type="submit">Mobil Logistics</button>
                        </form>
                    </div>
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">Sənədin Qaimə Nömrəsi</th>
                        <th scope="col">Sənədin Göndərilmə Tarixi</th>
                        <th scope="col">Sənəd Göndərən Təşkilatın Adı</th>
                        <th scope="col">Sənədin Məzmunu</th>
                        <th scope="col">Qeyd</th>
                        <th scope="col">Əməliyyatlar</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @forelse($sentDocuments as $sentDocument)
                            <tr id="item-{{$sentDocument->getAttribute('id')}}">
                            <th>{{$loop->iteration}}</th>
                            <td>{{$sentDocument->getRelationValue('companies')->getAttribute('name')}}</td>
                            <td>{{$sentDocument->getAttribute('overhead_num')}}</td>
                            <td>{{optional($sentDocument->getAttribute('sent_date'))->format('d/m/y')}}</td>
                            <td>{{$sentDocument->getAttribute('organization')}}</td>
                            <td>{{$sentDocument->getAttribute('content')}}</td>
                            <td>{{$sentDocument->getAttribute('note')}}</td>
                            @can('update', App\Models\SentDocument::class)
                                <td>
                                    <div class="btn-sm-group">
                                            <a href="{{route('sent-documents.show', $sentDocument)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                            <a href="{{route('sent-documents.edit', $sentDocument)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                            <a href="{{route('sent-documents.destroy', $sentDocument)}}" delete data-name="{{$sentDocument->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
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
@endsection
