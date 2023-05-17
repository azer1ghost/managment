@extends('layouts.main')

@section('title', trans('translates.navbar.supplier'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('suppliers.index')">
            @lang('translates.navbar.supplier')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{$data->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <div class=" row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-input::text name="name" :label="trans('translates.columns.name')" :value="$data->getAttribute('name')" width="6" class="pr-3"/>
                    <x-input::text name="voen" label="voen" :value="$data->getAttribute('voen')" width="6" class="pr-2" />
                    <x-input::text name="phone" :label="trans('translates.columns.phone')" :value="$data->getAttribute('phone')" width="6" class="pr-2" />
                    <x-input::text name="email" :label="trans('translates.columns.email')" :value="$data->getAttribute('email')" width="6" class="pr-2" />
                    <x-input::textarea name="note" :label="trans('translates.placeholders.note')" :value="$data->getAttribute('note')" width="6" class="pr-2" />
                </div>
            </div>
        </div>
        @if($action)
            <x-input::submit :value="trans('translates.buttons.save')"/>
        @endif
    </form>
    @if($method != 'POST')
        <div class="my-5">
            <x-documents :documents="$data->documents" :title="trans('translates.navbar.document')" />
            <x-document-upload :id="$data->id" model="Supplier"/>
        </div>
    @endif
@endsection
@section('scripts')

    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
        </script>
    @endif
@endsection
