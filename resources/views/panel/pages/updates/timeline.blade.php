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
                        <a class="btn btn-outline-danger" href="{{route('updates.index', ['type' => 'table'])}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <a class="btn btn-outline-success float-right" href="{{route('updates.create')}}">@lang('translates.buttons.create')</a>
                <a class="btn btn-outline-primary float-right mr-3" href="{{route('updates.index', ['type' => 'table'])}}">Table view</a>
            </div>
        </div>
        @if($updates->count())
            <div id="timeline-container">
                <div>
                    <div class="item mt-4">
                        <div id="timeline">
                            <div>
                                @foreach($updates as $date => $update)
                                    <section class="year">
                                        <h3>{{\Carbon\Carbon::parse($date)->format('Y F d')}}</h3>
                                        @foreach($update as $subUpdate)
                                            @php($parentRoute = auth()->user()->can('update', $subUpdate) ? route('updates.edit', $subUpdate) : route('updates.show', $subUpdate))
                                            <section>
                                                <ul>
                                                    <li style="font-weight: 900;font-size: 18px" data-toggle="tooltip" title="{{\App\Models\Update::statuses()[$subUpdate->status]}}"><a href="{{$parentRoute}}">{{$subUpdate->name}}</a></li>
                                                    @foreach($subUpdate->updates as $subUpdates)
                                                        @php($childRoute  = auth()->user()->can('update', $subUpdates) ? route('updates.edit', $subUpdates) : route('updates.show', $subUpdates))
                                                        <li style="font-size: 12px" data-toggle="tooltip" title="{{\App\Models\Update::statuses()[$subUpdates->status]}}"><a href="{{$childRoute}}">{{$subUpdates->name}}</a></li>
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