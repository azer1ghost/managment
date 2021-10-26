@extends('layouts.main')

@section('title', __('translates.navbar.update'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('updates.index', ['type' => 'table'])">
            @lang('translates.navbar.update')
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
                    <x-input::text  name="name"  :value="optional($data)->getAttribute('name')"  label="Update name"  width="6" class="pr-3" />
                    <div class="col-12 col-md-6 pr-3">
                        <label for="data-user_id">Update Status</label>
                        <select name="status" id="data-user_id" class="form-control">
                            <option value="" selected disabled>Select status</option>
                            @foreach($statuses as $index => $status)
                                <option @if(optional($data)->getAttribute('status') === $index) selected @endif value="{{$index}}">{{$status}}</option>
                            @endforeach
                        </select>
                        @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                    <div class="col-12 col-md-6 pr-3 mb-3">
                        <label for="data-parent_id">Update parent</label>
                        <select name="parent_id" id="data-parent_id" class="form-control">
                            <option value="" selected>Select parent</option>
                            @foreach($updates as $index => $update)
                                <option @if(optional($data)->getAttribute('parent_id') === $index) selected @endif value="{{$index}}">{{$update}}</option>
                            @endforeach
                        </select>
                        @error('parent_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div id="date-container">
                        <x-input::text name="datetime" :label="__('translates.fields.date')" value="{{optional($data)->getAttribute('datetime') ?? now()->format('Y-m-d')}}" type="text" width="12" class="pr-2" />
                    </div>
                    <x-input::textarea name="content" :value="optional($data)->getAttribute('content')" label="Update content"  width="12" class="pr-3" />
                </div>
            </div>
        </div>
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
    </form>
    @if($method != "POST")
        <livewire:commentable :commentable="$data" :url="str_replace('/edit', '', url()->current())"/>
    @endif
@endsection
@section('scripts')
        <script>
            $( "input[name='datetime']" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                showAnim: "slideDown",
                minDate: '-1m',
                maxDate: new Date()
            });

            function checkParent(parent = 'select[name="parent_id"]'){
                if($(parent).val().length === 0){
                    $('#date-container').show().find(':input').attr('disabled', false);
                }else{
                    $('#date-container').hide().find(':input').attr('disabled', true);
                }
            }

            checkParent();

            $('select[name="parent_id"]').change(function (){
                checkParent();
            });

            @if(is_null($action))
                $('form :input').attr('disabled', true)
            @endif
        </script>
@endsection
