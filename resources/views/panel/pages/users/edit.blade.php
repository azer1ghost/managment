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
                        <form class="col-md-12 form-row" action="{{$action}}" method="post" enctype="multipart/form-data">
                            @csrf @method($method)
                            <div class="col-md-3">
                                <x-input::image name="avatar" :value="optional($data)->getAttribute('avatar')"/>
                            </div>
                            <!-- Main -->
                            <div class="col-md-9">
                                <p class="text-muted mb-2">PERSONAL</p>
                                <hr class="my-2">
                                <div class="row">
                                    <x-input::text  name="name"    :value="optional($data)->getAttribute('name')"    width="4" class="pr-1" required=""/>
                                    <x-input::text  name="surname" :value="optional($data)->getAttribute('surname')" width="4" class="pr-1" />
                                    <x-input::text  name="father"  :value="optional($data)->getAttribute('father')"  width="4" class="pr-1" label="Father's name" />
                                </div>
                                <!-- Employment -->
                                <p class="text-muted mb-2">EMPLOYMENT</p>
                                <hr class="my-2">
                                <div class="row">
                                    <x-input::text    name="position"      :value="optional($data)->getAttribute('position')"    width="6"  class="pr-1" label="Position"/>
                                    <x-input::select  name="department_id" :value="optional($data)->getRelationValue('department')->getAttribute('id')"  width="6"  class="pr-1" :options="$departments" label="Department" />
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <!-- Passport -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">PASSPORT</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::select  name="serial_pattern" :value="optional($data)->getAttribute('serial_pattern')" label="Serial" width="1" class="p-0"   :options="['AA' => 'AA','AZE' => 'AZE']"/>
                                <x-input::text    name="serial" :value="optional($data)->getAttribute('serial')" label=" "   width="3" class="pr-1"  placeholder="Enter serial number"/>
                                <x-input::text    name="fin"    :value="optional($data)->getAttribute('fin')"    label="FIN"    width="2" class="pr-1" />
                                <x-input::select  name="gender" :value="optional($data)->getAttribute('gender')" :options="['male','female']" width="2" class="pr-1" />
                                <x-input::date    name="birthday" :value="optional($data)->getAttribute('birthday')" width="4" class="pr-1" />
                                <!-- Contact -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">CONTACT</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::text   name="phone_coop" :value="optional($data)->getAttribute('phone_coop')"   label="Cooperative number" width="3" class="pr-1" id="phone"/>
                                <x-input::text   name="phone"      :value="optional($data)->getAttribute('phone')"        label="Personal number"    width="3" class="pr-1" />
                                <x-input::email  name="email_coop" :value="optional($data)->getAttribute('email_coop')"   label="Cooperative Email"  width="3" class="pr-1" />
                                <x-input::email  name="email"      :value="optional($data)->getAttribute('email')"        label="Personal Email"     width="3" class="pr-1"  required=""/>
                                <!-- Address -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">ADDRESS</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::select  name="country"   :value="optional($data)->getAttribute('country')"  width="3" class="pr-1" :options="['Azerbaijan','Turkey']"/>
                                <x-input::select  name="city"      :value="optional($data)->getAttribute('city')"     width="3" class="pr-1" :options="['Baku','Sumgayit']"/>
                                <x-input::text    name="address"   :value="optional($data)->getAttribute('address')"  width="6" class="pr-1" />
                                <x-input::select  name="role_id"   :value="optional($data)->getRelationValue('role')->getAttribute('id')"  width="3" class="pr-1" :options="$roles" label="Role"/>
                            @if(is_null($data))
                                    <x-input::text type="password" name="password" width="6" class="pr-1" />
                                    <x-input::text type="password" name="password_confirmation" width="6" class="pr-1" label="Password Confirmation"/>
                                @endif
                                @if($action)
                                <x-input::submit/>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
            $('input[type="file"]').attr('disabled', true)
            $('select').attr('disabled', true)
            $('textarea').attr('readonly', true)
        </script>
    @endif
@endsection