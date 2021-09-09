@extends('layouts.main')

@section('title', __('translates.navbar.account'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <x-sidebar/>
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">@lang('translates.navbar.account')</div>
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
                                    <x-input::select  name="position_id"   :value="optional(auth()->user()->getRelationValue('position'))->getAttribute('id')"   width="6"  class="pr-1" :options="$positions" label="Position" />
                                    <x-input::select  name="department_id" :value="auth()->user()->getRelationValue('department')->getAttribute('id')" width="6"  class="pr-1" :options="$departments" label="Department" />
                                    <x-input::select  name="company_id"    :value="auth()->user()->getRelationValue('company')->getAttribute('id')"  width="6"  class="pr-1"   :options="$companies" label="Company" />
                                    @if (auth()->user()->isDeveloper())
                                        <x-input::text name="verify_code" readonly :value="auth()->user()->getAttribute('verify_code')"   width="6"  class="pr-1" label="Verify Code"/>
                                    @endif
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
                                <x-input::select  name="country"   :value="auth()->user()->getAttribute('country')"  width="3" class="pr-1" :options="['Azerbaijan' => 'Azerbaijan', 'Turkey' => 'Turkey']"/>
                                <x-input::select  name="city"      :value="auth()->user()->getAttribute('city')"     width="3" class="pr-1" :options="['Baku' => 'Baku', 'Sumgayit' => 'Sumgayit']"/>
                                <x-input::text    name="address"   :value="auth()->user()->getAttribute('address')"  width="6" class="pr-1" />
                                <x-input::text    name="password" width="6" class="pr-1" type="password" />
                                <x-input::text    name="password_confirmation" width="6" class="pr-1" label="Password Confirmation" type="password"/>
                                <x-input::select  name="role_id"   :value="auth()->user()->getRelationValue('role')->getAttribute('id')"  width="3" class="pr-1" :options="$roles" label="Role"/>

                                <div class="col-md-12">
                                    <p class="text-muted mb-2">PERMISSIONS</p>
                                    <p class="text-muted my-2">All</p>
                                    <div class="form-check">
                                        <input class="form-check-input" @if (Str::of(auth()->user()->getAttribute('permissions'))->trim() == 'all')) checked @endif type="checkbox" name="all_perms" value="all" id="perm-0">
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
                                                <input class="form-check-input" @if (Str::contains(auth()->user()->getAttribute('permissions'),$perm)) checked @endif type="checkbox" name="perms[]" value="{{$perm}}" id="perm-{{$loop->iteration}}">
                                                <label class="form-check-label" for="perm-{{$loop->iteration}}">
                                                    {{$perm}}
                                                </label>
                                            </div>
                                            @if (!Str::contains($nextPerm, $type) || $loop->first) </div> @endif
                                        @endforeach
                                    </div>
                                    @error("perms") <p class="text-danger">{{$message}}</p> @enderror
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <p class="text-muted mb-2">USER DEFAULTS</p>
                                    @livewire('show-user-defaults', ['user' => auth()->user(), 'action' => route('account.save', auth()->user())])
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
    @php // 1, 2 => Admin, President @endphp
    @if(!in_array(auth()->user()->getRelationValue('role')->getAttribute('id'), array(1, 2)))
        <script>
            $('select[name="role_id"]').attr('disabled', true)
            $('select[name="department_id"]').attr('disabled', true)
            $('select[name="company_id"]').attr('disabled', true)
            $('#perm-0').parent().parent().hide()
        </script>
    @endif
@endsection
