<form action="{{$action}}" id="createForm" method="POST" class=" form-row mb-5">
    @if($method == null)
        @can('update', $inquiry)
            <div class="col-12 my-4 pl-0">
                <a class="btn btn-outline-success" href="{{route('inquiry.edit', $inquiry)}}">Edit</a>
            </div>
        @endcan
    @endif

    @if(app()->environment('production') &&
        (
            ($method != 'POST' || auth()->user()->getAttribute('department_id') == \App\Models\Department::SALES) && !is_null($client)
        )
    )
        <div class="col-12 text-center">
            <h4>@lang('translates.fields.client')</h4>
            <div class="row">
                <div class="col-12 col-md-4">
                    <p>@lang('translates.columns.name'): {{$client->getAttribute('name')}}</p>
                </div>

                <div class="col-12 col-md-4">
                    <p>VOEN/GOEN: {{$client->getAttribute('voen')}}</p>
                </div>

                <div class="col-12 col-md-4">
                    <p>@lang('translates.fields.phone'): {{$client->getAttribute('phone')}}</p>
                </div>
                <input type="hidden" name="client_id" value="{{$client->getAttribute('id')}}">
            </div>
        </div>
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

    <x-input::text wire:ignore name="date" readonly :label="__('translates.fields.date')" value="{{$datetime->format('d-m-Y')}}" type="text" width="3" class="pr-2" />
    <x-input::text wire:ignore name="time" :label="__('translates.fields.time')" value="{{$datetime->format('H:i')}}" type="time" width="3" class="pr-2" />


    <input type="hidden" name="company_id" wire:model="selected.company">
    <input type="hidden" name="backUrl" wire:model="backUrl">

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
                <select @if($formField['id'] == \App\Models\Inquiry::STATUS_PARAMETER && $isRedirected) disabled @endif {{$formField['attributes']}} class="form-control" name="parameters[{{$formField['id']}}]" id="{{$formField['name']}}" wire:model="selected.{{$formField['name']}}">
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
                </span>
            @endif
        </div>
    @endforeach

    @if($selected['company'])
        <x-input::textarea name="note"  :value="$note"  label="Note"   width="12" rows="4"/>
        <div class="col-md-3">
            <div>
                <input wire:model="selected.is_out" type="radio" name="is_out" id="is_out1" value="0">
                <label class="form-check-label" for="is_out1">
                    @lang('translates.inquiries.types.from_customers')
                </label>
            </div>
            <div>
                <input type="radio" wire:model="selected.is_out" name="is_out" id="is_out" value="1">
                <label class="form-check-label" for="is_out">
                    @lang('translates.inquiries.types.from_us')
                </label>
            </div>
        </div>
{{--    <x-input::select name="redirected" :options="$operators" label="Redirect" width="4" class="pr-2" />--}}
    @endif

    @if(auth()->user()->hasPermission('checkRejectedReason-inquiry') && optional($inquiry->getParameter('status'))->getAttribute('id') == \App\Models\Inquiry::REJECTED))
        <div class="col-md-3">
            <div>
                <input type="radio" name="checking" id="checking" @if($inquiry->getAttribute('checking') == 0) checked @endif value="0">
                <label class="form-check-label" for="checking">
                    Imtina səbəbi uygun deyil
                </label>
            </div>
            <div>
                <input type="radio" name="checking" id="checking1" @if($inquiry->getAttribute('checking') == 1) checked @endif value="1">
                <label class="form-check-label" for="checking1">
                    İmtina sebebi duzgundur
                </label>
            </div>
        </div>
    @endif

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


