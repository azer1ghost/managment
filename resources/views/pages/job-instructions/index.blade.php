@extends('layouts.main')

@section('title', trans('translates.navbar.job_instruction'))

@section('content')
{!! $jobInstructions->getAttribute('instruction') !!}
@endsection
