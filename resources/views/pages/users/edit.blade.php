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
        @bind($data)

        <div class="col-md-2 px-0">
            @if (!is_null($action))
                <x-input::image name="avatar" :value="optional($data)->getAttribute('avatar')"/>
            @else
                <img src="{{image(optional($data)->getAttribute('avatar'))}}" alt="user" class="img-fluid"}}>
            @endif
            <div>
                @if(auth()->user()->isDeveloper() && !$data->isDeveloper() && !$data->isDisabled())
                    <a href="{{route('users.loginAs', $data)}}"
                       class="dropdown-item-text text-decoration-none"
                    >
                        <i class="fal fa-user pr-2 text-info"></i>Login as
                    </a>
                @endif
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-10 px-0 pl-3">
            <p class="text-muted mb-2">@lang('translates.fields.personal')</p>
            <hr class="my-2">
            <div class="row mr-0">
                <x-form-group  class="pr-3 col-12 col-lg-6">
                    <x-form-input name="name"/>
                </x-form-group>
                <x-form-group  class="pr-3 col-12 col-lg-6">
                    <x-form-input name="surname"/>
                </x-form-group>
                <x-form-group  class="pr-3 col-12 col-lg-6">
                    <x-form-input name="father"  :label="__('translates.fields.father')"/>
                </x-form-group>
            </div>
            <!-- Employment -->
            <p class="text-muted mb-2">@lang('translates.fields.employment')</p>
            <hr class="my-2">
            <div class="row mr-0">
                <x-form-group  class="pr-3 col-12 col-lg-6">
                    <x-form-select name="department_id" :options="$departments" :label="__('translates.fields.department')" />
                </x-form-group>

                @if (auth()->user()->isDirector())
                    <x-form-group  class="pr-3 col-12 col-lg-6">
                        <x-form-select  name="position_id" :options="$directorPositions" :label="__('translates.fields.department')" />
                    </x-form-group>
                @else
                    <x-form-group  class="pr-3 col-12 col-lg-6">
                        <x-form-select  name="position_id" :options="$positions" :label="__('translates.fields.position')" />
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6">
                        <x-form-select  name="official_position_id" :options="$positions" :label="__('translates.fields.position')" />
                    </x-form-group>
                @endif
                <x-form-group  class="pr-3 col-12 col-lg-6">
                    <x-form-select  name="company_id" :options="$companies" :label="__('translates.fields.company')" />
                </x-form-group>
                @if (auth()->user()->isDeveloper() && !is_null($data))
                    <x-form-group  class="pr-3 col-12 col-lg-6">
                        <x-form-input name="verify_code"  readonly label="Verify Code" />
                    </x-form-group>
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
            <x-form-group :label="__('translates.fields.serial')"  class="pr-3 col-12 col-lg-6">
                <x-form-select name="serial_pattern" :options="$serial_pattern"  />
            </x-form-group>
            <x-form-group  class="pr-3 col-12 col-lg-6">
                <x-form-input name="fin"   label="FIN" />
            </x-form-group>
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
                <p class="text-muted mb-2">@lang('translates.fields.contact')</p>
                <hr class="my-2">
            </div>
            <x-form-group  class="pr-3 col-12 col-lg-6">
                <x-form-input name="phone_coop"   :label="__('translates.fields.phone_coop')" />
            </x-form-group>
            <x-form-group  class="pr-3 col-12 col-lg-6">
                <x-form-input name="phone"   :label="__('translates.fields.phone_private')" />
            </x-form-group>
            <x-form-group  class="pr-3 col-12 col-lg-6">
                <x-form-input type="email" name="email_coop" :label="__('translates.fields.email_coop')" placeholder="Cooperative Email" />
            </x-form-group>
            <x-form-group  class="pr-3 col-12 col-lg-6">
                <x-form-input type="email" name="email" :label="__('translates.fields.email_private')" placeholder="Personal Email"  required=""/>
            </x-form-group>
            <!-- Address -->
            <div class="col-md-12 px-0">
                <br>
                <p class="text-muted mb-2">@lang('translates.columns.adress')</p>
                <hr class="my-2">
            </div>
            <x-form-group  class="pr-3 col-12 col-lg-6">
                <x-form-select name="country" :options="['Azerbaijan' => 'Azerbaijan', 'Turkey' => 'Turkey']" :label="__('translates.fields.country')" />
            </x-form-group>
            <x-form-group  class="pr-3 col-12 col-lg-6">
                <x-form-select name="city" :options="['Baku' => 'Baku', 'Sumgayit' => 'Sumgayit']" :label="__('translates.fields.city')" />
            </x-form-group>
            <x-form-group  class="pr-3 col-12 col-lg-6">
                <x-form-input  name="address" :label="__('translates.fields.address')"/>
            </x-form-group>
            @if(auth()->user()->isDeveloper())
                <x-form-group  class="pr-3 col-12 col-lg-6">
                    <x-form-input type="password" :bind="false"  name="password" :label="__('translates.fields.password')"  autocomplete="off"/>
                </x-form-group>
                <x-form-group  class="pr-3 col-12 col-lg-6">
                    <x-form-input type="password"  name="password_confirmation" :label="__('translates.fields.password_confirm')" autocomplete="off"/>
                </x-form-group>
            @endif
            @if(!is_null($data))
                <x-form-group  class="pr-3 col-12 col-lg-6">
                    <x-form-select name="role_id" :options="$roles" :label="__('translates.fields.role')" />
                </x-form-group>
                <x-form-group :label="__('translates.fields.default_lang')" class="pr-3 col-12 col-lg-6">
                    <x-form-select name="default_lang" :options="config('app.locales')" />
                </x-form-group>
            @endif
            <x-form-group  class="pr-3 col-12 col-lg-6">
                <x-form-input type="number" name="order" label="Order" />
            </x-form-group>
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
        @endbind

    </form>
    @if($method != 'POST')
        <div class="my-5">
            <x-documents :documents="$data->documents" :title="trans('translates.files.personal_work')" />
            <x-document-upload :id="$data->id" model="User"/>
        </div>
    @endif
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
