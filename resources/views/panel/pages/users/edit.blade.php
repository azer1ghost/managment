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
                                <x-input::image name="image"/>
                            </div>
                            <!-- Main -->
                            <div class="col-md-9">
                                <p class="text-muted mb-2">PERSONAL</p>
                                <hr class="my-2">
                                <div class="row">
                                    <x-input::text required="" name="name" :value="optional($data)->name" width="4" class="pr-1" />
                                    <x-input::text required="" name="surname" :value="optional($data)->surname" width="4" class="pr-1" />
                                    <x-input::text required="" name="father" :value="optional($data)->father" label="Father's name" width="4" class="pr-1" />
                                </div>
                                <!-- Employment -->
                                <p class="text-muted mb-2">EMPLOYMENT</p>
                                <hr class="my-2">
                                <div class="row">
                                    <x-input::text required="" :value="optional($data)->position" name="position" label="Position" width="6" class="pr-1" />
                                    <x-input::select :options="['Marketing','Call center']" :value="optional($data)->department" name="department" width="6" class="pr-1" />
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <!-- Passport -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">PASSPORT</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::select :options="['AA','AZE']"  name="serialPattern" label="Serial" width="1" class="p-0" />
                                <x-input::text required="" name="serial" label=" " placeholder="Enter serial number" width="3" class="pr-1" />
                                <x-input::text required="" :value="optional($data)->fin" name="fin" label="FIN" width="2" class="pr-1" />
                                <x-input::select :options="['male','female']" name="gender" width="2" class="pr-1" />
                                <x-input::date required="" name="birthday" width="4" class="pr-1" />
                                <!-- Contact -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">CONTACT</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::text required="" :value="optional($data)->phone_coop" id="phone" name="phone_coop" label="Cooperative number" width="3" class="pr-1" />
                                <x-input::text required="" :value="optional($data)->phone" name="phone" label="Personal number" width="3" class="pr-1" />
                                <x-input::email required="" :value="optional($data)->email_coop" name="email_coop" label="Cooperative Email" width="3" class="pr-1" />
                                <x-input::email required="" :value="optional($data)->email" name="email" label="Personal Email"  width="3" class="pr-1" />
                                <!-- Address -->
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">ADDRESS</p>
                                    <hr class="my-2">
                                </div>
                                <x-input::select :options="['Azerbaijan','Turkey']" :value="optional($data)->country" name="country" width="3" class="pr-1" />
                                <x-input::select :options="['Baku','Sumgayit']" :value="optional($data)->city" name="city" width="3" class="pr-1" />
                                <x-input::text required="" :value="optional($data)->address" name="address" width="6" class="pr-1" />
                                <x-input::text name="password" width="6" class="pr-1" />
                                <x-input::text name="password_confirm" width="6" class="pr-1" />
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