<form action="{{$action}}" id="createForm" method="POST" class=" form-row mb-5">
    @if($method == null)
        @can('update', $barcode)
            <div class="col-12 my-4 pl-0">
                <a class="btn btn-outline-success" href="{{route('barcode.edit', $barcode)}}">Edit</a>
            </div>
        @endcan
    @endif

    @csrf @method($method)

    <div wire:loading.delay class="col-12">
        <div style="position: absolute;right: 0;top: -25px">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>

    <div wire:offline class="col-12">
        <div class="text-danger" title="Internet connection loss" style="position: absolute;right: 0;top: -25px">
            <img src="{{asset('assets/images/svgs/exclamation-triangle-solid.svg')}}" alt="internet-loss" height="25">
        </div>
    </div>

        <x-input::text name="code" :value="$code" label="Barcode" width="3" rows="4"/>
        <x-input::text name="customer" :value="$customer" :label="trans('translates.fields.client')" width="3" rows="4"/>
        <x-input::text name="phone" :value="$phone" :label="trans('translates.fields.phone')" width="3" rows="4"/>

        <div class="form-group col-md-3">
            <label for="data-mediator">İşi göndərən</label>
            <select class="form-control" name="mediator_id" id="data-mediator">
                <option value="null" disabled selected>Vasitəçi {{trans('translates.placeholders.choose')}}</option>
                @foreach($mediators as $mediator)
                    <option value="{{$mediator->getAttribute('id')}}" @if($mediator->getAttribute('id') == $barcode->getAttribute('mediator_id')) selected @endif>{{$mediator->getAttribute('fullname')}}
                    </option>
                @endforeach
            </select>
        </div>

    @foreach($formFields as $formField)
        @if($formField['type'] === 'select' && count($formField['options']) == 0)
            @continue
        @endif
        @php
            $label = $formField['label'][app()->getLocale()] ?? $formField['label']['en'];
            $placeholder = $formField['placeholder'][app()->getLocale()] ?? $formField['placeholder']['en'];
        @endphp
        <div class="form-group col-md-3">
            <label for="{{$formField['name']}}">
                {{$label}}
            </label>
            @if($formField['type'] === 'select')
                <select {{$formField['attributes']}} class="form-control" name="parameters[{{$formField['id']}}]" id="{{$formField['name']}}" wire:model="selected.{{$formField['name']}}">
                    <option value="null" disabled selected>{{$label}} {{__('translates.placeholders.choose')}}</option>
                    @foreach($formField['options'] as $option)
                        @php($optionText = $option['text'][app()->getLocale()] ?? $option['text']['en'])
                        <option value="{{$option['id']}}">
                            {{$optionText}}
                        </option>
                    @endforeach
                </select>
            @else
                <input class="{{$formField['class'] ?? null}} form-control" {{$formField['attributes']}}  name="parameters[{{$formField['id']}}]" placeholder="{{$placeholder}}" type="{{$formField['type']}}" wire:model.lazy="selected.{{$formField['name']}}">
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $formField['message'] ?? null }}</strong>
                </span>
            @endif
        </div>
    @endforeach
        <x-input::textarea name="note" :value="$note" label="Note" width="12" rows="4"/>

    @if($action)
        <div class="col-12">
            <button class="btn btn-outline-primary float-right">@lang('translates.buttons.save')</button>
        </div>
    @endif

</form>

@if(is_null($action))
    @push('scripts')
        <script>
            $("#createForm :input").attr("disabled", true);
        </script>
    @endpush
@endif
