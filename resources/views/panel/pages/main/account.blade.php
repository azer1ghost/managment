@extends('layouts.main')

@section('title', __('translates.navbar.account'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.account')
        </x-bread-crumb-link>
    </x-bread-crumb>

    <form class="form-row px-2" action="{{route('account.save',auth()->user())}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="col-md-2 px-0">
            <x-input::image name="avatar" class="px-0" :value="auth()->user()->getAttribute('avatar')"/>
        </div>
        <!-- Main -->
        <div class="col-md-10 px-0 pl-3">
            <p class="text-muted mb-2">@lang('translates.fields.personal')</p>
            <hr class="my-2">
            <div class="row mr-0">
                <x-input::text  name="name"    :label="__('translates.fields.name')"    :placeholder="__('translates.placeholders.name')" :value="auth()->user()->getAttribute('name')"    width="4" class="pr-0" required=""/>
                <x-input::text  name="surname" :label="__('translates.fields.surname')" :placeholder="__('translates.placeholders.surname')" :value="auth()->user()->getAttribute('surname')" width="4" class="pr-0" />
                <x-input::text  name="father"  :label="__('translates.fields.father')"  :placeholder="__('translates.placeholders.father')" :value="auth()->user()->getAttribute('father')"  width="4" class="pr-0" />
            </div>
            <!-- Employment -->
            <p class="text-muted mb-2">@lang('translates.fields.employment')</p>
            <hr class="my-2">
            <div class="row mr-0">
                <x-input::select default="1" name="department_id" :label="__('translates.fields.department')" :value="auth()->user()->getRelationValue('department')->getAttribute('id')" width="6"  class="pr-0" :options="$departments" />
                @if (auth()->user()->isDirector())
                    <x-input::select default="1" name="position_id" :label="__('translates.fields.position')" :value="optional(auth()->user()->getRelationValue('position'))->getAttribute('id')"   width="6"  class="pr-0" :options="$directorPositions" />
                @else
                    <x-input::select default="1" name="position_id"  :label="__('translates.fields.position')" :value="optional(auth()->user()->getRelationValue('position'))->getAttribute('id')"   width="6"  class="pr-0" :options="$positions" />
                @endif
                <x-input::select default="1" name="company_id"  :label="__('translates.fields.company')"  :value="auth()->user()->getRelationValue('company')->getAttribute('id')"  width="6"  class="pr-0"   :options="$companies" />
                @if (auth()->user()->isDeveloper())
                    <x-input::text name="verify_code" readonly :value="auth()->user()->getAttribute('verify_code')"   width="6"  class="pr-0" label="Verify Code"/>
                @endif
            </div>
        </div>
        <div class="form-row mx-0 col-md-12">
            <!-- Passport -->
            <div class="col-md-12 px-0">
                <br>
                <p class="text-muted mb-2">@lang('translates.fields.passport')</p>
                <hr class="my-2">
            </div>
            <x-input::select  name="serial_pattern" :label="__('translates.fields.serial')" :value="auth()->user()->getAttribute('serial_pattern')" width="1" class="p-0"   :options="['AA' => 'AA','AZE' => 'AZE']"/>
            <x-input::text    name="serial"   :value="auth()->user()->getAttribute('serial')"  label=" "   width="3" class="pr-0"  :placeholder="__('translates.placeholders.serial_pattern')"/>
            <x-input::text    name="fin"      :value="auth()->user()->getAttribute('fin')"     label="FIN"    width="2" class="pr-0" />
            <x-input::select  name="gender"   :label="__('translates.fields.gender')" :value="auth()->user()->getAttribute('gender')"  :options="[__('translates.gender.male'), __('translates.gender.female')]" width="2" class="pr-0" />
            <x-input::date    name="birthday" :label="__('translates.fields.birthday')" :value="auth()->user()->getAttribute('birthday')" width="4" class="pr-0" />
            <!-- Contact -->
            <div class="col-md-12 px-0">
                <br>
                <p class="text-muted mb-2">@lang('translates.fields.contact')</p>
                <hr class="my-2">
            </div>
            <x-input::text   name="phone_coop" :value="auth()->user()->getAttribute('phone_coop')"  :label="__('translates.fields.phone_coop')"     width="3"  class="pr-0" id="phone"/>
            <x-input::text   name="phone"      :value="auth()->user()->getAttribute('phone')"       :label="__('translates.fields.phone_private')"  width="3"  class="pr-0" />
            <x-input::email  name="email_coop" :value="auth()->user()->getAttribute('email_coop')"  :label="__('translates.fields.email_coop')"     width="3"  class="pr-0" />
            <x-input::email  name="email"      :value="auth()->user()->getAttribute('email')"       :label="__('translates.fields.email_private')"  width="3"  class="pr-0"  required=""/>
            <!-- Address -->
            <div class="col-md-12 px-0">
                <br>
                <p class="text-muted mb-2">@lang('translates.fields.address')</p>
                <hr class="my-2">
            </div>
            <x-input::select  name="country"   :value="auth()->user()->getAttribute('country')"  width="3" class="pr-0" :options="['Azerbaijan' => __('translates.countries.Azerbaijan'), 'Turkey' => __('translates.countries.Turkey')]"/>
            <x-input::select  name="city"      :value="auth()->user()->getAttribute('city')"     width="3" class="pr-0" :options="['Baku' => __('translates.cities.Baku'), 'Sumgayit' => __('translates.cities.Sumgayit')]"/>
            <x-input::text    name="address"   :label="__('translates.fields.address')" :value="auth()->user()->getAttribute('address')"  width="6" class="pr-0" />
            <x-input::text    name="password"  :label="__('translates.fields.password')"  width="6" class="pr-0" type="password" autocomplete="off"/>
            <x-input::text    name="password_confirmation" :label="__('translates.fields.password_confirm')" width="6" class="pr-0"  type="password" autocomplete="off"/>
            <x-input::select  name="role_id"   :label="__('translates.fields.role')" :value="auth()->user()->getRelationValue('role')->getAttribute('id')"  width="3" class="pr-0" :options="$roles" />

            <div class="col-md-12 px-0">
                <p class="text-muted mb-2">PERMISSIONS</p>
                <div class="px-2">
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
            </div>
            <div class="col-md-12">
                <br>
                <p class="text-muted mb-2">USER DEFAULTS</p>
                @livewire('show-user-defaults', ['user' => auth()->user(), 'action' => route('account.save', auth()->user())])
            </div>
            <x-input::submit/>
        </div>
    </form>
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
            $('select[name="position_id"]').attr('disabled', true)
            $('#perm-0').parent().parent().hide()
        </script>
    @endif
@endsection
