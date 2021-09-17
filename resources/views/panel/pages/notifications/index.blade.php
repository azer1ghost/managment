@extends('layouts.main')

@section('title', __('translates.navbar.notification'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.notification')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('notifications.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('notifications.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">User</th>
                        <th scope="col">Type</th>
                        <th scope="col">Read at</th>
                        <th scope="col">Sent at</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($notifications as $notification)
                        <tr>
                            <th scope="row">{{$notification->getRelationValue('notifiable')->getAttribute('id')}}</th>
                            <td>{{$notification->getRelationValue('notifiable')->getAttribute('fullname')}}</td>
                            <td>{{explode('\\', $notification->getAttribute('type'))[3]}}</td>
                            <td>{{optional($notification->getAttribute('read_at'))->format('d-m-Y H:i:s')}}</td>
                            <td>{{optional($notification->getAttribute('created_at'))->format('d-m-Y H:i:s')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $notification)
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#show-notification-{{$loop->iteration}}">
                                            <i class="fal fa-eye"></i>
                                        </button>
                                        <div class="modal fade" tabindex="-1" id="show-notification-{{$loop->iteration}}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Modal title</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <pre>{!! print_r($notification->getAttribute('data'), true) !!}</pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="6">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">Empty for now</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$notifications->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection