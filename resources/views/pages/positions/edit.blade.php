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
                    <x-translate>
                        @foreach(config('app.locales') as $key => $locale)
                            <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                                <div class="row">
                                    <x-input::text  name="translate[name][{{$key}}]"  :value="optional($data)->getTranslation('name', $key)"     label="Position name {{$key}}"     width="6" class="pr-3" />
                                </div>
                            </div>
                        @endforeach
                    </x-translate>
                    <x-input::select  name="role_id" :value="optional($data)->getAttribute('role_id')" label="Position role"  width="6" class="pr-3" :options="$roles"/>
                    <x-input::select  default="1" name="department_id"  :value="optional($data)->getAttribute('department_id')"  label="Position department"  width="6" class="pr-3" :options="$departments"/>
                    <x-input::number  default="1" name="order" :value="optional($data)->getAttribute('order')"  label="Position Order"  width="6" class="pr-3" />
                </div>
                @if(auth()->user()->isDeveloper())
                    <x-permissions :model="$data" :action="$action" />
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
