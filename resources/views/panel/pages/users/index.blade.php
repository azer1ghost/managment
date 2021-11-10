@extends('layouts.main')

@section('title', __('translates.navbar.user'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.user')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('users.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-8 col-md-6 mb-3">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('users.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\User::class)
                <div class="col-4 col-md-2 mb-3">
                    <a class="btn btn-outline-success float-right" href="{{route('users.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <div class="row m-0">
                    <div class="col-4 col-md-2 pl-0 mb-3">
                        <select name="limit" class="custom-select">
                            @foreach([25, 50, 100] as $size)
                                <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-8 col-md-3 pl-0 mb-3">
                        <select name="company" class="custom-select">
                            <option value="">@lang('translates.fields.company') @lang('translates.placeholders.choose')</option>
                            @foreach($companies as $company)
                                <option @if(request()->get('company') == $company->id) selected @endif value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            <select class="form-control" name="type">
                                @foreach ($types as $index => $type)
                                    <option @if (request()->get('type') == $index) selected @endif value="{{$index}}">@lang('translates.users.types.' . $type)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">@lang('translates.columns.full_name')</th>
                        <th scope="col">FIN</th>
                        <th scope="col">@lang('translates.columns.email')</th>
                        <th scope="col">@lang('translates.columns.phone')</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">@lang('translates.columns.department')</th>
                        <th scope="col">@lang('translates.columns.role')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <th scope="row"><img src="{{image($user->getAttribute('avatar'))}}" alt="user" class="profile" /></th>
                            <td>{{$user->getAttribute('fullname')}}
                                @if($user->getAttribute('id') === auth()->id()) <h5 class="d-inline"><span class="badge badge-info text-white">Me</span></h5> @endif
                                @if($user->getAttribute('disabled_at')) <span class="text-danger">(@lang('translates.disabled'))</span> @endif
                            </td>
                            <td>{{$user->getAttribute('fin')}}</td>
                            <td>{{$user->getAttribute('email_coop')}}</td>
                            <td>{{$user->getAttribute('phone_coop')}}</td>
                            <td>{{$user->getRelationValue('company')->getAttribute('name')}}</td>
                            <td>{{$user->getRelationValue('department')->getAttribute('name')}}</td>
                            <td>{{$user->getRelationValue('role')->getAttribute('name')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $user)
                                        <a href="{{ $user->getAttribute('id') === auth()->id() ? route('account') : route('users.show', $user)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                        @unless ($user->getAttribute('id') === auth()->id())
                                            @can('update', $user)
                                                <a href="{{ $user->getAttribute('id') === auth()->id() ? route('account') : route('users.edit', $user)}}" class="btn btn-sm btn-outline-success">
                                                    <i class="fal fa-pen"></i>
                                                </a>
                                            @endcan
                                            @can('delete', $user)
                                                <a href="{{route('users.destroy', $user)}}" delete data-name="{{$user->getAttribute('fullname')}}" class="btn btn-sm btn-outline-danger" >
                                                    <i class="fal fa-trash"></i>
                                                </a>
                                            @endcan
                                        @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="9">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.not_found')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="float-right">
                    {{$users->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        $('select').change(function(){
            this.form.submit();
        });
    </script>
@endsection