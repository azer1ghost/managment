@extends('layouts.main')

@section('title', __('translates.navbar.announcement'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('announcements.index')">
            @lang('translates.navbar.announcement')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{optional($data)->getAttribute('key')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST">
        @method($method) @csrf
        <div class="tab-content row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <div class="form-group col-6">
                        <label for="user_id">@lang('translates.columns.user')</label><br/>
                        <select class="userSelector form-control" multiple data-selected-text-format="count"
                                data-width="fit" title="@lang('translates.filters.select')" name="users[]" id="user_id">
                            @foreach($users as $user)
                                <option @if(in_array($user->id, explode("," , $data->getAttribute('users')))) selected
                                        @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('users')
                        <p class="text-danger">{{$message}}</p>
                    @enderror

                    <x-input::text name="class" :value="$data->getAttribute('class')" label="Class" width="6" class="pr-3"/>
                    <x-input::text name="title" :value="$data->getAttribute('title')" label="Title" width="6" class="pr-3"/>
                    <x-input::text name="repeat_rate" :value="$data->getAttribute('repeat_rate')" label="Repeat Rate" width="6" class="pr-3"/>

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-will_notify_at">Will Notify At</label>
                        <input type="text" placeholder="Will Notify At" name="will_notify_at"
                               value="{{$data->getAttribute('will_notify_at')}}" id="data-will_notify_at" class="form-control custom-single-daterange">
                    </div>
                    @error('will_notify_at')
                        <p class="text-danger">{{$message}}</p>
                    @enderror

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-will_end_at">Will  End At</label>
                        <input type="text" placeholder="Will Notify At" name="will_end_at"
                               value="{{$data->getAttribute('will_end_at')}}" id="data-will_end_at" class="form-control custom-single-daterange" >
                    </div>
                    @error('will_end_at')
                        <p class="text-danger">{{$message}}</p>
                    @enderror

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0 form-check pr-3">
                        <input type="checkbox" name="status" @if($data->getAttribute('status') === true) checked @endif  id=status>
                        <label class="form-check-label" for="status">Status</label>
                    </div>
                    @error('status')
                        <p class="text-danger">{{$message}}</p>
                    @enderror

                    <div class="col-12 pr-3 mt-2">
                        <label for="tinymce">Detail</label>
                        <textarea name="detail" id="tinymce" class="tinyMCE form-control">{{$data->getAttribute('detail')}}</textarea>
                    </div>

                    <div class="col-12 my-3">
                        <x-permissions :model="$data" :action="$action" />
                    </div>
                </div>
            </div>
        </div>

        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
    </form>
@endsection


