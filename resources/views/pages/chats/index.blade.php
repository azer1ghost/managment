@extends('layouts.main')

@section('title', 'Chats')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
           Chat
        </x-bread-crumb-link>
    </x-bread-crumb>
<div class="col">
    <div class="alert alert-primary">Coming Soon</div>
</div>
@endsection