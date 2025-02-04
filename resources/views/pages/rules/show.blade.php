@extends('layouts.main')

@section('title', trans('translates.navbar.rule'))

@section('content')
    <div class="container">
        <h2>{!! $rule->getAttribute('name') !!}</h2>

        {!! $rule->getAttribute('content') !!}
    </div>
@endsection
