@extends('layouts.main')

@section('title', __('translates.navbar.user'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('users.index')">
            @lang('translates.navbar.user')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('fullname')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form class="form-row mr-0" action="{{$action}}" method="post" enctype="multipart/form-data">
        @csrf @method($method)
        <div class="col-md-2 px-0">
            @if (!is_null($action))
                <x-input::image name="avatar" :value="optional($data)->getAttribute('avatar')"/>
            @else
                <img src="{{image(optional($data)->getAttribute('avatar'))}}" alt="user" class="img-fluid"}}>
            @endif
        </div>
        <!-- Main -->
        <div class="col-md-10 px-0 pl-3">
            <p class="text-muted mb-2">PERSONAL</p>
            <hr class="my-2">
            <div class="row mr-0">
                <x-input::text  name="name"    :value="optional($data)->getAttribute('name')"    width="4" class="pr-0" required=""/>
                <x-input::text  name="surname" :value="optional($data)->getAttribute('surname')" width="4" class="pr-0" />
                <x-input::text  name="father"  :value="optional($data)->getAttribute('father')"  width="4" class="pr-0" label="Father's name" />
            </div>
            <!-- Employment -->
            <p class="text-muted mb-2">EMPLOYMENT</p>
            <hr class="my-2">
            <div class="row mr-0">
                <x-input::select default="1" name="department_id" :value="optional(optional($data)->getRelationValue('department'))->getAttribute('id')"  width="6"  class="pr-0" :options="$departments" label="Department" />
                @if (auth()->user()->isDirector())
                    <x-input::select default="1" name="position_id"   :value="optional(optional($data)->getRelationValue('position'))->getAttribute('id')"   width="6"  class="pr-0" :options="$directorPositions" label="Position" />
                @else
                    <x-input::select default="1"  name="position_id"   :value="optional(optional($data)->getRelationValue('position'))->getAttribute('id')"    width="6"  class="pr-0" :options="$positions"   label="Position" />
                    <x-input::select default="1"  name="official_position_id"   :value="optional($data)->getAttribute('official_position_id')"    width="6"  class="pr-0" :options="$positions"   label="Official Position" />
                @endif
                <x-input::select default="1" name="company_id"    :value="optional(optional($data)->getRelationValue('company'))->getAttribute('id')"     width="6"  class="pr-0" :options="$companies"   label="Company" />
                @if (auth()->user()->isDeveloper() && !is_null($data))
                    <x-input::text name="verify_code" readonly :value="optional($data)->getAttribute('verify_code')"   width="6"  class="pr-0" label="Verify Code"/>
                @endif
            </div>
        </div>
        <div class="form-row mx-0 col-md-12">
            <!-- Passport -->
            <div class="col-md-12 px-0">
                <br>
                <p class="text-muted mb-2">PASSPORT</p>
                <hr class="my-2">
            </div>
            <x-input::select  name="serial_pattern" :label="__('translates.fields.serial')" :value="optional($data)->getAttribute('serial_pattern')" width="1" class="p-0"   :options="$serial_pattern"/>            <x-input::text    name="serial" :value="optional($data)->getAttribute('serial')" label=" "   width="3" class="pr-0"  placeholder="Enter serial number"/>
            <x-input::text    name="fin"    :value="optional($data)->getAttribute('fin')"    label="FIN"    width="2" class="pr-0" />
            <div class="form-group col-12 col-md-2">
                <label for="data-gender">{{__('translates.fields.gender')}}</label>
                <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="data-gender" style="padding: .375rem 0.75rem !important;">
                    <option disabled selected value="null">{{__('translates.fields.gender')}} {{__('translates.placeholders.choose')}}</option>
                    @foreach([__('translates.gender.male'), __('translates.gender.female')] as $key => $option)
                        <option @if ($key === optional($data)->getAttribute('gender')) selected @endif value="{{$key}}">{{$option}}</option>
                    @endforeach
                </select>
                @error('gender')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <x-input::date    name="birthday" :value="optional($data)->getAttribute('birthday')" width="4" class="pr-0" />
            <!-- Contact -->
            <div class="col-md-12 px-0">
                <br>
                <p class="text-muted mb-2">CONTACT</p>
                <hr class="my-2">
            </div>
            <x-input::text   name="phone_coop" :value="optional($data)->getAttribute('phone_coop')"   label="Cooperative number" width="3" class="pr-0" />
            <x-input::text   name="phone"      :value="optional($data)->getAttribute('phone')"        label="Personal number"    width="3" class="pr-0" />
            <x-input::email  name="email_coop" :value="optional($data)->getAttribute('email_coop')"   label="Cooperative Email"  width="3" class="pr-0" />
            <x-input::email  name="email"      :value="optional($data)->getAttribute('email')"        label="Personal Email"     width="3" class="pr-0"  required=""/>
            <!-- Address -->
            <div class="col-md-12 px-0">
                <br>
                <p class="text-muted mb-2">ADDRESS</p>
                <hr class="my-2">
            </div>
            <x-input::select  name="country"   :value="optional($data)->getAttribute('country')"  width="3" class="pr-0" :options="['Azerbaijan' => 'Azerbaijan', 'Turkey' => 'Turkey']"/>
            <x-input::select  name="city"      :value="optional($data)->getAttribute('city')"     width="3" class="pr-0" :options="['Baku' => 'Baku', 'Sumgayit' => 'Sumgayit']"/>
            <x-input::text    name="address"   :value="optional($data)->getAttribute('address')"  width="6" class="pr-0" />
            @if(auth()->user()->isDeveloper())
                <x-input::text type="password" name="password" width="6" class="pr-0" autocomplete="off"/>
                <x-input::text type="password" name="password_confirmation" width="6" class="pr-0" label="Password Confirmation" autocomplete="off"/>
            @endif
            @if(!is_null($data))
                <x-input::select  name="role_id"   :value="optional(optional($data)->getRelationValue('role'))->getAttribute('id')"  width="3" class="pr-0" :options="$roles" label="Role"/>
                <x-input::select  name="default_lang" :default="1"   :label="__('translates.fields.default_lang')" :value="optional($data)->getAttribute('default_lang')"  width="3" class="pr-0" :options="config('app.locales')" />
            @endif
            <x-input::number  name="order" :value="optional($data)->getAttribute('order')" width="4" class="pr-0" />

        @if(auth()->user()->isDeveloper())
                <x-permissions :model="$data" :action="$action" />
                <div class="col-md-12 px-0">
                    <br>
                    <p class="text-muted mb-2">USER DEFAULTS</p>
                    @livewire('show-user-defaults',['user' => $data, 'action' => $action])
                </div>
            @endif
            @if($action)
                <x-input::submit/>
            @endif
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
    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
            $('input[type="checkbox"]').attr('disabled', true)
            $('#check-perms').parent().hide()
            $('select').attr('disabled', true)
        </script>
    @endif
@endsection
