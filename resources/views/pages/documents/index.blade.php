@extends('layouts.main')

@section('title', __('translates.navbar.document'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.document')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('documents.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-12 col-md-6" >
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('documents.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="input-group mb-3">
                    <select class="select2 form-control" name="user_id" id="user_id">
                        <option value="">@lang('translates.general.user_select')</option>
                        @foreach($users as $user)
                            <option @if(request()->get('user_id') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-8 mb-3 pt-2 d-flex align-items-center">
                <p class="mb-0"> @lang('translates.total_items', ['count' => $documents->count(), 'total' => $documents->total()])</p>
                <div class="input-group col-md-3">
                    <select name="limit" class="custom-select" id="size">
                        @foreach([25, 50, 100, 250] as $size)
                            <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">File</th>
                        <th scope="col">Type</th>
                        <th scope="col">Size</th>
                        <th scope="col">User</th>
                        <th scope="col">Module</th>
                        <th scope="col">Module Name</th>
                        <th scope="col">@lang('translates.columns.created_at')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($documents as $document)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$document->getAttribute('name')}}</td>
                            <td>{{$document->getAttribute('file')}}</td>
                            <td>{{$document->getAttribute('type')}}</td>
                            <td>{{human_filesize($document->getAttribute('size'), 2)}}</td>
                            <td>{{$document->getRelationValue('user')->getAttribute('fullname')}}</td>
                            <td>{{$document->module()}}</td>
                            <td>{{$document->getRelationValue('documentable')->getMainColumn()}}</td>
                            <td>{{$document->getAttribute('created_at')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $document)
                                        @php($route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document))
                                        <a href="{{$route}}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $document)
                                        <a href="{{route('documents.edit', $document)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $document)
                                        <a href="{{route('documents.destroy', $document)}}" delete data-name="{{$document->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
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
            </div>
            <div class="col-12">
                <div class="float-right">
                    {{$documents->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        $('select[name="user_id"]').change(function () {
            $(this).form().submit();
        });
    </script>
@endsection