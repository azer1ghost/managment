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
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('users.index')}}"><i class="fal fa-times"></i></a>
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
                    <div class="col-8 col-md-2 pl-0 mb-3">
                        <select name="company" class="custom-select">
                            <option value="">@lang('translates.fields.company') @lang('translates.placeholders.choose')</option>
                            @foreach($companies as $company)
                                <option @if(request()->get('company') == $company->id) selected @endif value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-8 col-md-2 pl-0 mb-3">
                        <select name="department" class="custom-select">
                            <option value="">@lang('translates.fields.department') @lang('translates.placeholders.choose')</option>
                            @foreach($departments as $department)
                                <option @if(request()->get('department') == $department->id) selected @endif value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-8 col-md-2 pl-0 mb-3">
                        <div class="input-group mb-3">
                            <select class="form-control" name="type">
                                @foreach ($types as $index => $type)
                                    <option @if (request()->get('type') == $index) selected @endif value="{{$index}}">@lang('translates.users.types.' . $type)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-8 col-md-2 pl-0 mb-3">
                        <div class="input-group mb-3">
                            <select class="form-control" name="status">
                                @foreach ($statuses as $index => $status)
                                    <option @if (request()->get('status') == $index) selected @endif value="{{$index}}">@lang('translates.users.statuses.' . $status)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

{{--                    <div class="col-4 col-md-1 pl-0 mb-3">--}}
{{--                        <select name="limit" class="custom-select">--}}
{{--                            @foreach([25, 50, 100] as $size)--}}
{{--                                <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
                    <div class="col-8 pt-2 d-flex align-items-center">
                        <p class="mb-0"> @lang('translates.total_items', ['count' => $users->count(), 'total' => $user_count])</p>
                    </div>
                </div>
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">İD</th>
                        <th scope="col"></th>
                        <th scope="col">@lang('translates.columns.full_name')</th>
                        <th scope="col">FIN</th>
                        <th scope="col">@lang('translates.columns.email')</th>
                        <th scope="col">@lang('translates.columns.phone')</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">@lang('translates.columns.department')</th>
                        <th scope="col">@lang('translates.fields.work_started_at')</th>
                        <th scope="col">@lang('translates.columns.role')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody id="sortableUser">
                    @forelse($users as $user)
                        <tr id="item-{{$user->getAttribute('id')}}">
                            <th>{{$user->getAttribute('id')}}</th>
                            <th scope="row"><img src="{{image($user->getAttribute('avatar'))}}" alt="user" class="profile sortable" /></th>
                            <td class="sortable">{{$user->getAttribute('fullname_with_position')}}
                                @if($user->getAttribute('id') === auth()->id()) <h5 class="d-inline"><span class="badge badge-info text-white">Me</span></h5> @endif
                                @if($user->getAttribute('disabled_at')) <span class="text-danger">(@lang('translates.disabled'))</span> @endif
                            </td>
                            <td>{{$user->getAttribute('fin')}}</td>
                            <td>{{$user->getAttribute('email_coop')}}</td>
                            <td>{{$user->getAttribute('phone_coop')}}</td>
                            <td>{{$user->getRelationValue('company')->getAttribute('name')}}</td>
                            <td>{{$user->getRelationValue('department')->getAttribute('name')}}</td>
                            <td>{{$user->getAttribute('started_at')}}</td>
                            <td>{{$user->getRelationValue('role')->getAttribute('name')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    <div class="dropdown">
                                        @can('view', $user)
                                            <a href="{{ $user->getAttribute('id') === auth()->id() ? route('account') : route('users.show', $user)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                        @endcan
                                        <button class="btn" type="button" id="inquiry_actions-{{$loop->iteration}}"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fal fa-ellipsis-v-alt"></i>
                                        </button>
                                        <div class="dropdown-menu custom-dropdown">
                                            @unless ($user->getAttribute('id') === auth()->id())
                                                @can('update', $user)
                                                    <a href="{{ $user->getAttribute('id') === auth()->id() ? route('account') : route('users.edit', $user)}}"
                                                       class="dropdown-item-text text-decoration-none"
                                                    >
                                                        <i class="fal fa-pen pr-2 text-success"></i>Edit
                                                    </a>
                                                @endcan
                                                @can('delete', $user)
                                                    <a href="{{route('users.destroy', $user)}}"
                                                       class="dropdown-item-text text-decoration-none"
                                                       delete data-name="{{$user->getAttribute('fullname')}}"
                                                    >
                                                        <i class="fal fa-trash pr-2 text-danger"></i>Delete
                                                    </a>
                                                @endcan
                                                @if(auth()->user()->isDeveloper() && !$user->isDeveloper() && !$user->isDisabled())
                                                    <a href="{{route('users.loginAs', $user)}}"
                                                       class="dropdown-item-text text-decoration-none"
                                                    >
                                                        <i class="fal fa-user pr-2 text-info"></i>Login as
                                                    </a>
                                                @endif
                                                @if(auth()->user()->hasPermission('manageStatus-user'))
                                                    @php
                                                        $route = $user->isDisabled() ? route('users.enable', $user) : route('users.disable', $user);
                                                        $icon =  $user->isDisabled() ? 'unlock' : 'lock';
                                                        $status = $user->isDisabled() ? 'enable' : 'disable';
                                                    @endphp
                                                    <a href="{{$route}}"
                                                       delete data-type="POST" data-name="{{$user->getAttribute('fullname')}}"
                                                       data-status="Are you sure to {{$status}}"
                                                       data-status-title="Confirm {{$status}} action"
                                                       class="dropdown-item-text text-decoration-none"
                                                    >
                                                        <i class="fal fa-user-{{$icon}} pr-2 text-info"></i>{{ucfirst($status)}}
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr id="item-{{$user->getAttribute('id')}}">
                    @empty
                        <tr>
                            <th colspan="20">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.not_found')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
{{--            <div class="col-12">--}}
{{--                <div class="float-right">--}}
{{--                    {{$users->appends(request()->input())->links()}}--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </form>
@endsection
@section('scripts')
    @if(auth()->user()->hasPermission('update-user') && is_null(request()->get('status')))
        <script>
            $(function () {
                $('#sortableUser').sortable({
                    axis: 'y',
                    handle: ".sortable",
                    update: function () {
                        var data = $(this).sortable('serialize');
                        $.ajax({
                            type: "POST",
                            data: data,
                            url: "{{route('user.sortable')}}",
                        });
                    }
                });
                $('#sortableUser').disableSelection();
            });
        </script>
    @endif
    <script>
        $('select').change(function(){
            this.form.submit();
        });
    </script>
@endsection