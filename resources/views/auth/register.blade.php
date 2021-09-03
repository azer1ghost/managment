@extends('layouts.main')

@section('title', __('translates.register.register'))

@section('content')
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container">
            <div class="card login-card">
                <div class="row no-gutters">
                    <div class="col-md-5">
                        <img src="https://source.unsplash.com/900x1100/?office" id="loginBackground" alt="login" class="login-card-img">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <div class="brand-wrapper">
                                <img src="{{asset('assets/images/logos/group.png')}}" alt="logo" class="logo">
                            </div>
                            <p class="login-card-description">@lang('translates.register.title')</p>
                            @php
                                $departments = \App\Models\Department::all()->pluck('name', 'id')->toArray();
                                $companies   = \App\Models\Company::all()->pluck('name', 'id')->toArray();
                            @endphp
                            <form method="POST" class="form-row" action="{{ route('register') }}">
                                @csrf
                                <x-input::text required="" name="name" width="6" :label="__('translates.register.name')" :placeholder="__('translates.placeholders.name')"/>
                                <x-input::text required="" name="surname" width="6" :label="__('translates.register.surname')" :placeholder="__('translates.placeholders.surname')"/>
                                <x-input::email required="" name="email_coop" :label="__('translates.register.mail_coop')" :placeholder="__('translates.placeholders.mail_coop')"/>
                                <x-input::select name="department_id"  width="6"  class="pr-1" :options="$departments" :label="__('translates.register.department')"  required=""/>
                                <x-input::select name="company_id"  width="6"  class="pr-1" :options="$companies" :label="__('translates.register.company')"  required=""/>
                                <x-input::text type="password" required="" name="password" width="6" :label="__('translates.register.password')" :placeholder="__('translates.placeholders.password')"/>
                                <x-input::text type="password" required="" name="password_confirmation" :label="__('translates.register.password_confirm')" :placeholder="__('translates.placeholders.password_confirm')" width="6"/>
                                <x-input::submit :value="__('translates.register.register')"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
