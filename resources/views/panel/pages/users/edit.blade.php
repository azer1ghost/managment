@extends('layouts.main')

@section('title', __('translates.navbar.user'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <x-sidebar/>
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">@lang('translates.navbar.user')</div>
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
                                    <x-input::select  name="company_id"    :value="optional($data)->getRelationValue('company')->getAttribute('id')"  width="6"  class="pr-1" :options="$companies" label="Company" />
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
                                @if(auth()->user()->isDeveloper() || auth()->user()->isAdministrator())
                                    <x-input::text type="password" name="password" width="6" class="pr-1" autocomplete="off"/>
                                    <x-input::text type="password" name="password_confirmation" width="6" class="pr-1" label="Password Confirmation" autocomplete="off"/>
                                @endif
                                <x-input::select  name="role_id"   :value="optional($data)->getRelationValue('role')->getAttribute('id')"  width="3" class="pr-1" :options="$roles" label="Role"/>
                                @if(auth()->user()->isDeveloper() || auth()->user()->isAdministrator())
                                    <div class="col-md-12">
                                        <p class="text-muted mb-2">PERMISSIONS</p>
                                        <p class="text-muted my-2">All</p>
                                        <div class="form-check">
                                            <input class="form-check-input" @if (Str::of(optional($data)->getAttribute('permissions'))->trim() == 'all')) checked @endif type="checkbox" name="all_perms" value="all" id="perm-0">
                                            <label class="form-check-label" for="perm-0">
                                                All
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="check-perms">
                                            <label class="form-check-label" for="check-perms">
                                                Choose All
                                            </label>
                                        </div>
                                        @error("all_perms") <p class="text-danger">{{$message}}</p> @enderror
                                        <div class="row">
                                            @php $perms = config('auth.permissions') @endphp
                                            @foreach ($perms as $index => $perm)
                                                @php
                                                    // next and previous permissions
                                                    $prevPerm = $perms[$index == 0 ?: $index - 1];
                                                    $nextPerm = $perms[$index == $loop->count - 1 ?: $index + 1];
                                                    // type of permission
                                                    $type  = strpos($perm, '-') ? substr($perm, strpos($perm, '-') + 1) : $perm;
                                                @endphp
                                                @if (!Str::contains($prevPerm, $type) || $loop->first)
                                                    <div class="col-12 col-md-4 my-2">
                                                        <p class="text-muted my-2">{{ucfirst($type)}}</p>
                                                        @endif
                                                        <div class="form-check">
                                                            <input class="form-check-input" @if (Str::contains(optional($data)->getAttribute('permissions'),$perm)) checked @endif type="checkbox" name="perms[]" value="{{$perm}}" id="perm-{{$loop->iteration}}">
                                                            <label class="form-check-label" for="perm-{{$loop->iteration}}">
                                                                {{$perm}}
                                                            </label>
                                                        </div>
                                                        @if (!Str::contains($nextPerm, $type) || $loop->first) </div> @endif
                                            @endforeach
                                        </div>
                                        @error("perms") <p class="text-danger">{{$message}}</p> @enderror
                                    </div>
                                @endif
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">USER DEFAULTS</p>
                                    @livewire('show-user-defaults',['user' => $data, 'action' => $action])
                                </div>
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
    <script>
        checkAll();
        $('#perm-0').change(function (){
            checkAll();
        });
        $('#check-perms').change(function (){
            if ($(this).prop('checked') == true) {
                $("input[name='perms[]']").map(function(){ $(this).prop('checked', true) });
            }else{
                $("input[name='perms[]']").map(function(){ $(this).prop('checked', false) });
            }
        });
        function checkAll(check = "perm-0"){
            if ($(`#${check}`).prop('checked') == true) {
                $("#check-perms").prop('disabled', true).parent('div').hide();
                $("input[name='perms[]']").map(function(){ $(this).prop('disabled',true).parent('div').parent('div').hide() });
            }else{
                $("#check-perms").prop('disabled', false).parent('div').show();
                $("input[name='perms[]']").map(function(){ $(this).prop('disabled',false).parent('div').parent('div').show() });
            }
        }
    </script>
    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
            $('input[type="checkbox"]').attr('disabled', true)
            $('#check-perms').parent().hide()
            $('select').attr('disabled', true)
        </script>
    @endif
@endsection
