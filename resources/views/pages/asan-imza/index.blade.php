@extends('layouts.main')

@section('title', __('translates.navbar.asan_imza'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.asan_imza')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('asan-imza.index')}}">
        <div class="row d-flex justify-content-between mb-2">

            <div class="col-8 col-md-3 pl-3 mb-3">
                <select name="company" class="custom-select">
                    <option value="">@lang('translates.fields.company') @lang('translates.placeholders.choose')</option>
                    @foreach($companies as $company)
                        <option @if(request()->get('company') == $company->id) selected @endif value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-8 col-md-3 pl-3 mb-3">
                <select name="department" class="custom-select">
                    <option value="">@lang('translates.fields.department') @lang('translates.placeholders.choose')</option>
                    @foreach($departments as $department)
                        <option @if(request()->get('department') == $department->id) selected @endif value="{{$department->id}}">{{$department->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-8 col-md-3 mr-md-auto mb-3">
                <select name="limit" class="custom-select">
                    @foreach([25, 50, 100] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto mb-3">
                @can('create', App\Models\AsanImza::class)
                    <a class="btn btn-outline-success" href="{{route('asan-imza.create')}}">@lang('translates.buttons.create')</a>
                @endcan
            </div>
            <table class="table table-responsive-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('translates.fields.user')</th>
                    <th scope="col">@lang('translates.fields.company')</th>
                    <th scope="col">@lang('translates.fields.department')</th>
                    <th scope="col">@lang('translates.fields.phone')</th>
                    <th scope="col">Asan ID</th>
                    <th scope="col">Pin 1</th>
                    <th scope="col">Pin 2</th>
                    <th scope="col">PUK</th>
                    <th scope="col">Status</th>
                    <th scope="col">@lang('translates.fields.actions')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($asan_imzas as $asan_imza)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$asan_imza->getRelationValue('user')->getFullnameWithPositionAttribute()}}</td>
                        <td>{{$asan_imza->getRelationValue('company')->getAttribute('name')}}</td>
                        <td>{{$asan_imza->getRelationValue('department')->getAttribute('name')}}</td>
                        <td>{{$asan_imza->getAttribute('phone')}}</td>
                        <td>{{$asan_imza->getAttribute('asan_id')}}</td>
                        <td>{{$asan_imza->getAttribute('pin1')}}</td>
                        <td>{{$asan_imza->getAttribute('pin2')}}</td>
                        <td>{{$asan_imza->getAttribute('puk')}}</td>
                        <td>@if($asan_imza->getAttribute('is_active') == 1) <span style="color: green" >@lang('translates.users.statuses.active')</span> @else <span style="color: red">@lang('translates.users.statuses.deactivate') @endif</td>
                        <td>
                            <div class="btn-sm-group">
                                @can('view', $asan_imza)
                                    <a href="{{route('asan-imza.show', $asan_imza)}}" class="btn btn-sm btn-outline-primary"> <i class="fal fa-eye"></i></a>
                                @endcan

                                @can('update', $asan_imza)
                                    <a href="{{route('asan-imza.edit', $asan_imza)}}" class="btn btn-sm btn-outline-success"> <i class="fal fa-pen"></i></a>
                                @endcan

                                @can('delete', $asan_imza)
                                    <a href="{{route('asan-imza.destroy', $asan_imza)}}" delete data-name="{{$asan_imza->getAttribute('name')}}" class="btn btn-sm btn-outline-danger"> <i class="fal fa-trash"></i> </a>
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
    </form>
@endsection
@section('scripts')
    <script>
        $('select').change(function () {
            this.form.submit();
        });
    </script>
@endsection
