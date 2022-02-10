@extends('layouts.main')
@section('title', __('translates.navbar.update'))
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.update')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="row">
    </div>
    <form action="{{route('updates.index')}}">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('updates.index', ['type' => 'table'])}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                @can('create', App\Models\Update::class)
                    <a class="btn btn-outline-success float-right" href="{{route('updates.create')}}">@lang('translates.buttons.create')</a>
                @endcan
                <a class="btn btn-outline-primary float-right mr-3" href="{{route('updates.index', ['type' => 'table'])}}">Table view</a>
            </div>
        </div>
        @if($updates->count())
            <div id="timeline-container">
                <div>
                    <div class="item mt-4">
                        <div id="timeline">
                            <div>
                                @php($statuses = \App\Models\Update::statuses())
                                @foreach($updates as $date => $update)
                                    <section class="year">
                                        @php($date = \Carbon\Carbon::parse($date))
                                        <h3>{{$date->year}} {{$date->monthName}} {{$date->day}}</h3>
                                        @foreach($update as $subUpdate)
                                            @php($parentRoute = route(auth()->user()->can('update', $subUpdate) ? 'updates.edit' : 'updates.show', $subUpdate))
                                            <section>
                                                <ul>
                                                    <li style="font-weight: 900;font-size: 18px" data-toggle="tooltip"
                                                        title="{{$statuses[$subUpdate->status]['name']}} @if($subUpdate->done_at) </br> Done at: {{$subUpdate->done_at}} @endif"
                                                    >
                                                        <a href="{{$parentRoute}}">
                                                            <span class="badge badge-{{$statuses[$subUpdate->status]['color']}}">
                                                                {{$statuses[$subUpdate->status]['name']}}
                                                            </span>
                                                            {{$subUpdate->name}}:
                                                            <span class="text-muted">{{$subUpdate->content}}</span>
                                                        </a>
                                                    </li>
                                                    @foreach($subUpdate->updates as $subUpdates)
                                                        @php($childRoute  = route(auth()->user()->can('update', $subUpdates) ? 'updates.edit' : 'updates.show', $subUpdates))
                                                        <li style="font-size: 12px" data-toggle="tooltip"
                                                            title="{{$statuses[$subUpdates->status]['name']}} @if($subUpdates->done_at) </br> Done at: {{$subUpdates->done_at}} @endif"
                                                        >
                                                            <a href="{{$childRoute}}"><i class="fa fa-circle text-{{$statuses[$subUpdates->status]['color']}}"></i> {{$subUpdates->name}}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </section>
                                        @endforeach
                                    </section>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center mt-5">
                <div class="col-6">
                    <div class="alert alert-danger text-center" role="alert">
                        Empty!
                    </div>
                </div>
            </div>
        @endif
    </form>
@endsection