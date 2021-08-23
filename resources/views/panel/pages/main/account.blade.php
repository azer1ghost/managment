@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <x-sidebar/>
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">@lang('user')</div>
                    <div class="card-body">
                        <form class="col-md-12 form-row" action="{{route('account.save',auth()->user())}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-3">
                                <x-input::image name="avatar" :value="auth()->user()->getAttribute('avatar')"/>
                            </div>
                            <!-- Main -->
                            <div class="col-md-9">
                                <p class="text-muted mb-2">PERSONAL</p>
                                <hr class="my-2">
                                <div class="row">
                                    <x-input::text  name="name"    :value="auth()->user()->getAttribute('name')"    width="4" class="pr-1" required=""/>
                                    <x-input::text  name="surname" :value="auth()->user()->getAttribute('surname')" width="4" class="pr-1" />
                                    <x-input::text  name="father"  :value="auth()->user()->getAttribute('father')"  width="4" class="pr-1" label="Father's name" />
                                </div>
                                <!-- Employment -->
                                <p class="text-muted mb-2">EMPLOYMENT</p>
                                <hr class="my-2">
                                <div class="row">
                                    <x-input::text    name="position"      :value="auth()->user()->getAttribute('position')"   width="6"  class="pr-1" label="Position"/>
                                    <x-input::select  name="department_id" :value="auth()->user()->getRelationValue('department')->getAttribute('id')" width="6"  class="pr-1" :options="$departments" label="Department" />
                                    <x-input::select  name="company_id"    :value="auth()->user()->getRelationValue('company')->getAttribute('id')"  width="6"  class="pr-1" :options="$companies" label="Company" />

                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <!-- Passport -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">PASSPORT</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::select  name="serial_pattern" :value="auth()->user()->getAttribute('serial_pattern')" label="Serial" width="1" class="p-0"   :options="['AA' => 'AA','AZE' => 'AZE']"/>
                                <x-input::text    name="serial"   :value="auth()->user()->getAttribute('serial')"  label=" "   width="3" class="pr-1"  placeholder="Enter serial number"/>
                                <x-input::text    name="fin"      :value="auth()->user()->getAttribute('fin')"     label="FIN"    width="2" class="pr-1" />
                                <x-input::select  name="gender"   :value="auth()->user()->getAttribute('gender')"  :options="['male','female']" width="2" class="pr-1" />
                                <x-input::date    name="birthday" :value="auth()->user()->getAttribute('birthday')" width="4" class="pr-1" />
                                <!-- Contact -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">CONTACT</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::text   name="phone_coop" :value="auth()->user()->getAttribute('phone_coop')"   label="Cooperative number" width="3" class="pr-1" id="phone"/>
                                <x-input::text   name="phone"      :value="auth()->user()->getAttribute('phone')"        label="Personal number"    width="3" class="pr-1" />
                                <x-input::email  name="email_coop" :value="auth()->user()->getAttribute('email_coop')"   label="Cooperative Email"  width="3" class="pr-1" />
                                <x-input::email  name="email"      :value="auth()->user()->getAttribute('email')"        label="Personal Email"     width="3" class="pr-1"  required=""/>
                                <!-- Address -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">ADDRESS</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::select  name="country"   :value="auth()->user()->getAttribute('country')"  width="3" class="pr-1" :options="['Azerbaijan','Turkey']"/>
                                <x-input::select  name="city"      :value="auth()->user()->getAttribute('city')"     width="3" class="pr-1" :options="['Baku','Sumgayit']"/>
                                <x-input::text    name="address"   :value="auth()->user()->getAttribute('address')"  width="6" class="pr-1" />
                                <x-input::text    name="password" width="6" class="pr-1" type="password" />
                                <x-input::text    name="password_confirmation" width="6" class="pr-1" label="Password Confirmation" type="password"/>
                                <x-input::select  name="role_id"   :value="auth()->user()->getRelationValue('role')->getAttribute('id')"  width="3" class="pr-1" :options="$roles" label="Role"/>
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">USER DEFAULTS</p>
                                    @livewire('show-user-defaults',['user' => auth()->id()])
                                </div>
                                <x-input::submit/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @php // 1, 2 => Admin, President @endphp
    @if(!in_array(auth()->user()->getRelationValue('role')->getAttribute('id'), array(1, 2)))
        <script>
            $('select[name="role_id"]').attr('disabled', true)
            $('select[name="department_id"]').attr('disabled', true)
            $('select[name="company_id"]').attr('disabled', true)
        </script>
    @endif
@endsection