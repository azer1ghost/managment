@extends('layouts.signature')

@section('content')
    <div class="container animate__animated  animate__fadeIn" xmlns:x-input="http://www.w3.org/1999/html">
        <div class="row d-flex align-items-center justify-content-center vh-100">
{{--            <x-logo class="animate__animated animate__flip" width="100px"></x-logo>--}}
            <form class="col-md-12 form-row" action="">
                <x-input::text name="name" width="4" class="pr-4" />
                <x-input::text name="surname" width="4" class="pr-4" />
                <x-input::text name="father" label="Father's name" width="4" class="pr-4" />
                <x-input::email required name="email" width="4" class="pr-4" />
                <x-input::date name="birthday" width="4" class="pr-4" />
                <x-input::select :options="['male','female']" name="gender" width="4" class="pr-4" />
                <x-input::text name="address" width="4" class="pr-4" />

                <x-input::submit/>
            </form>
        </div>
    </div>
@endsection