@extends('layouts.main')
@section('title', 'Login')
@section('content')
<form method="POST" action="{{ route('myguard.register.submit') }}">
    @csrf
    <div>
        <label for="email">name</label>
        <input id="email" type="text" name="fullname" value="{{ old('voen') }}" required autofocus>
    </div>

    <div>
        <label for="email">Voen</label>
        <input id="email" type="text" name="voen" value="{{ old('voen') }}" required autofocus>
    </div>

    <div>
        <label for="password">{{ __('Password') }}</label>
        <input id="password" type="password" name="password" required>
    </div>
    <div>
        <button type="submit">
            {{ __('Login') }}
        </button>
    </div>
    {{$errors}}
</form>
@endsection