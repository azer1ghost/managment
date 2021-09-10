@extends('layouts.main')

@section('title', __('translates.navbar.task'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <x-sidebar/>
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <a href="{{route('tasks.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                            <i class="fa fa-arrow-left"></i>
                            @lang('translates.buttons.back')
                        </a>
                        @lang('translates.navbar.task')
                    </div>
                    <div class="card-body">
                        <form action="{{$action}}" method="POST" enctype="multipart/form-data">
                            @method($method) @csrf
                            <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
                            <div class="tab-content row mt-4" >
                                <div class="form-group col-12">
                                    <div class="row">
                                        <x-input::text    name="name"  :value="optional($data)->getAttribute('name')"  label="Task name"  width="6" class="pr-3" />
                                        <x-input::select  default='1' name="department_id"  :value="optional($data)->getAttribute('department_id')"  label="Task department"  width="6" class="pr-3" :options="$departments"/>
                                    </div>
                                </div>
                            </div>
                            @if($action)
                                <x-input::submit  :value="__('translates.buttons.save')" />
                            @endif
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
        </script>
    @endif
@endsection
