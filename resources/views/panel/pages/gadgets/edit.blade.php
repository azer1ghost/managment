@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar/>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('gadgets.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                        <i class="fa fa-arrow-left"></i>
                        @lang('translates.buttons.back')
                    </a>
                    gadgets
                </div>
                <div class="card-body">
                    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
                        @method($method) @csrf
                        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
                        <div class="tab-content row mt-4" >
                            <x-input::text     name="key"       :value="optional($data)->getAttribute('key')"      label="Gadget key"     width="6"  class="pr-3" />
                            <x-input::text     name="name"      :value="optional($data)->getAttribute('name')"     label="Gadget name"    width="6"  class="pr-3" />
                            <x-input::text     name="icon"      :value="optional($data)->getAttribute('icon')"     label="Gadget icon"    width="6"  class="pr-3" />
                            <x-input::number   name="order"     :value="optional($data)->getAttribute('order')"    label="Gadget order"   width="6"  class="pr-3" />
                            <div class="form-group col-12 col-md-6">
                                <label for="data-color" class="">Gadget Color</label>
                                <input type="color" class="form-control" name="color" id="data-color">
                            </div>
                            <x-input::textarea name="query"     :value="optional($data)->getAttribute('query')"    label="Gadget query"   width="12" class="pr-3" rows="3"/>
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

@if(is_null($action))
@section('scripts')
    <script>
        $('input').attr('readonly', true)
        $('select').attr('disabled', true)
        $('input[type="file"]').attr('disabled', true)
        $('textarea').attr('readonly', true)
    </script>
@endsection
@endif
