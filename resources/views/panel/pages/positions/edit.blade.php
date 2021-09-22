@extends('layouts.main')

@section('title', __('translates.navbar.position'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('positions.index')">
            @lang('translates.navbar.position')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class="tab-content row mt-4" >
            <div class="form-group col-12">
                <div class="row">
                    <x-input::text    name="name"           :value="optional($data)->getAttribute('name')"           label="Position name"  width="6" class="pr-3" />
                    <x-input::select  name="role_id"        :value="optional($data)->getAttribute('role_id')"        label="Position role"  width="6" class="pr-3" :options="$roles"/>
                    <x-input::select  default="1" name="department_id"  :value="optional($data)->getAttribute('department_id')"  label="Position department"  width="6" class="pr-3" :options="$departments"/>
                </div>
                @if(auth()->user()->isDeveloper())
                    <div class="col-md-12 px-0">
                        <p class="text-muted mb-2">PERMISSIONS</p>
                        <div class="px-2">
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
                    </div>
                @endif
            </div>
        </div>
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
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
