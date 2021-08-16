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
                                <x-input::image name="avatar" :value="optional($data)->avatar"/>
                            </div>
                            <!-- Main -->
                            <div class="col-md-9">
                                <p class="text-muted mb-2">PERSONAL</p>
                                <hr class="my-2">
                                <div class="row">
                                    <x-input::text  name="name"    :value="optional($data)->name"    width="4" class="pr-1" required=""/>
                                    <x-input::text  name="surname" :value="optional($data)->surname" width="4" class="pr-1" />
                                    <x-input::text  name="father"  :value="optional($data)->father"  width="4" class="pr-1" label="Father's name" />
                                </div>
                                <!-- Employment -->
                                <p class="text-muted mb-2">EMPLOYMENT</p>
                                <hr class="my-2">
                                <div class="row">
                                    <x-input::text    name="position"   :value="optional($data)->position"   width="6"  class="pr-1" label="Position"/>
                                    <x-input::select  name="department" :value="optional($data)->department" width="6"  class="pr-1" :options="['Marketing','Call center']" />
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <!-- Passport -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">PASSPORT</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::select  name="serial_pattern" :value="optional($data)->serial_pattern" label="Serial" width="1" class="p-0"   :options="['AA' => 'AA','AZE' => 'AZE']"/>
                                <x-input::text    name="serial" :value="optional($data)->serial" label=" "   width="3" class="pr-1"  placeholder="Enter serial number"/>
                                <x-input::text    name="fin"    :value="optional($data)->fin"    label="FIN"    width="2" class="pr-1" />
                                <x-input::select  name="gender" :value="optional($data)->gender" :options="['male','female']" width="2" class="pr-1" />
                                <x-input::date    name="birthday" :value="optional($data)->birthday" width="4" class="pr-1" />
                                <!-- Contact -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">CONTACT</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::text   name="phone_coop" :value="optional($data)->phone_coop"   label="Cooperative number" width="3" class="pr-1" id="phone"/>
                                <x-input::text   name="phone"      :value="optional($data)->phone"        label="Personal number"    width="3" class="pr-1" />
                                <x-input::email  name="email_coop" :value="optional($data)->email_coop"   label="Cooperative Email"  width="3" class="pr-1" />
                                <x-input::email  name="email"      :value="optional($data)->email"        label="Personal Email"     width="3" class="pr-1"  required=""/>
                                <!-- Address -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">ADDRESS</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::select  name="country"   :value="optional($data)->country"  width="3" class="pr-1" :options="['Azerbaijan','Turkey']"/>
                                <x-input::select  name="city"      :value="optional($data)->city"     width="3" class="pr-1" :options="['Baku','Sumgayit']"/>
                                <x-input::text    name="address"   :value="optional($data)->address"  width="6" class="pr-1" />
                                @if(is_null($data))
                                    <x-input::text type="password" name="password" width="6" class="pr-1" />
                                    <x-input::text type="password" name="password_confirmation" width="6" class="pr-1" label="Password Confirmation"/>
                                @endif
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
    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
            $('select').attr('disabled', true)
            $('textarea').attr('readonly', true)
        </script>
    @endif
@endsection