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
            Geri Qayıdan İşlərin Cədvəli
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                @can('create', \App\Models\ReturnWork::class)
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('return-works.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                <table class="table table-responsive-sm table-hover" id="returnWorks">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Dəyişikliyin tarixi</th>

                        <th scope="col">@lang('translates.parameters.types.operation')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($returnWorks as $returnWork)
                         <tr>

                            <td>{{$loop->iteration}}</td>


                            @can('update', App\Models\ReturnWork::class)
                                <td>
                                    <div class="btn-sm-group">
                                            <a href="{{route('changes.show', $returnWork)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                            <a href="{{route('changes.edit', $returnWork)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                            <a href="{{route('changes.destroy', $returnWork)}}" delete data-name="{{$returnWork->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
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
                        {{$returnWorks->appends(request()->input())->links()}}
                    </div>
            </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#returnWorks').DataTable();
        });
    </script>
@endsection