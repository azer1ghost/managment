@extends('layouts.main')

@section('title', __('translates.navbar.report'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('reports.index')">
            @lang('translates.navbar.report')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            {{$parent->getRelationValue('chief')->getAttribute('fullname_with_position')}}
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="row m-0">
        <table class="table table-responsive-sm table-hover text-capitalize">

        </table>
    </div>
@endsection