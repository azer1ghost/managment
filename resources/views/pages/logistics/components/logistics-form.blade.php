<form action="{{$action}}" method="POST" enctype="multipart/form-data" id="logistics-form">

    @if(!is_null($data) && $method != 'PUT')
        @can('update', $data)
            <div class="col-12 my-4 pl-0">
                <a class="btn btn-outline-success" href="{{route('logistics.edit', $data)}}">Edit</a>
            </div>
        @endcan
    @endif
    @method($method) @csrf
    <div wire:loading.delay class="col-12 mr-2" style="position: absolute;right:20px">
        <div style="position: absolute;right: 0;top: -25px">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>
    <div class=" row my-5">
        <div class="form-group col-12">
            <div class="row m-0">
{{--                <div class="form-group col-12 col-md-6" wire:ignore>--}}
{{--                    <input type="number" id="data-number" name="number">--}}
{{--                    <label class="form-number" for="data-number">@lang('translates.general.number')</label>--}}
{{--                </div>--}}
                <x-input::number wire:ignore name="number"  :label="__('translates.fields.number')" value="{{$data->getAttribute('number')}}" width="3" class="pr-3 " />
                    <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-client-type">{{trans('translates.fields.clientName')}}</label><br/>
                    <div class="d-flex align-items-center">
                        <select name="client_id" id="data-client-type" data-url="{{route('clients.search')}}" class="custom-select2" style="width: 100% !important;" required>
                            @if(is_numeric(optional($data)->getAttribute('client_id')))
                                <option value="{{optional($data)->getAttribute('client_id')}}">{{optional($data)->getRelationValue('client')->getAttribute('fullname_with_voen')}}</option>
                            @endif
                        </select>
                        @if(is_numeric(optional($data)->getAttribute('client_id')))
                            @can('update', \App\Models\Client::find(optional($data)->getAttribute('client_id')))
                                <a target="_blank" href="{{route('clients.edit', optional($data)->getAttribute('client_id'))}}" class="btn btn-outline-primary ml-3">
                                    <i class="fa fa-pen"></i>
                                </a>
                            @endcan
                        @endif
                    </div>
                </div>

                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-transport_type">@lang('translates.general.transport_type_choose')</label>
                    <select name="transport_type" id="data-transport_type" class="form-control">
                        <option disabled >@lang('translates.general.transport_type_choose')</option>
                        @foreach($transportTypes as $key => $transportType)
                            <option @if(optional($data)->getAttribute('transport_type') == $transportType ) selected @endif value="{{$transportType}}">
                                @lang('translates.transport_types.' . $key)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label class="d-block" for="reference_id">{{__('translates.navbar.reference')}}</label>
                    <select id="reference_id" class="select2"
                            name="reference_id"
                            data-width="fit" title="{{__('translates.filters.select')}}">
                        <option value="">@lang('translates.filters.select')</option>
                        @foreach($users as $user)
                            <option
                                    @if($user->getAttribute('id') == optional($data)->getAttribute('reference_id')) selected @endif
                            value="{{$user->getAttribute('id')}}">
                                {{$user->getAttribute('full_name_with_position')}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-service_id">@lang('translates.general.work_service')</label>
                    <input wire:ignore disabled type="text" class="form-control" id="data-service_id" value="{{\App\Models\Service::find($selected['service_id'])->name}}">
                    <input type="hidden" @if($this->subServices->isEmpty()) name="service_id" @endif wire:model="selected.service_id">
                </div>

                @if(!$this->subServices->isEmpty())
                    <div class="form-group col-12 col-md-6" wire:ignore>
                        <label for="data-service_id">@lang('translates.general.work_service_type')</label>
                        <select name="service_id" id="data-service_id" class="form-control">
                            @foreach($this->subServices as $service)
                                <option @if(optional($data)->getAttribute('service_id') === $service->id ) selected @endif
                                value="{{ $service->getAttribute('id') }}">
                                    {{ $service->getAttribute('name') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group col-12 col-md-3" wire:ignore>
                    <label for="data-status">@lang('translates.general.status_choose')</label>
                    <select name="status" id="data-status" class="form-control">
                        <option disabled >@lang('translates.general.status_choose')</option>
                        @foreach($statuses as $key => $status)
                            <option @if(optional($data)->getAttribute('status') == $status ) selected @endif value="{{$status}}">
                                @lang('translates.logistics_statuses.' . $key)
                            </option>
                        @endforeach
                    </select>
                </div>

                @foreach($parameters as $parameter)
                    @if(in_array('hideOnPost', explode(' ', $parameter->attributes)) && $method == 'POST')
                        @continue
                    @endif

                    @switch($parameter->type)
                        @case('text')
                            <div class="form-group col-12 col-md-3" @if(!auth()->user()->hasPermission('update-logistics') && in_array('finance', explode(' ', $parameter->attributes))) style="display: none" @endif wire:ignore>
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <input type="text" data-label="{{$parameter->getTranslation('label', 'az')}}" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control parameters parameters[{{$parameter->id}}]" placeholder="{{$parameter->placeholder}}" wire:model="logisticsParameters.{{$parameter->name}}">
                            </div>
                            @break
                        @case('number')
                            <div class="form-group col-12 col-md-3" @if(!auth()->user()->hasPermission('update-logistics') && in_array('finance', explode(' ', $parameter->attributes))) style="display: none" @endif wire:ignore>
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <input type="number" data-label="{{$parameter->getTranslation('label', 'az')}}" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control parameters" placeholder="{{$parameter->placeholder}}" wire:model="logisticsParameters.{{$parameter->name}}">
                            </div>
                        @break
                        @case('select')
                            <div class="form-group col-12 col-md-3" @if(!auth()->user()->hasPermission('update-logistics') && in_array('finance', explode(' ', $parameter->attributes))) style="display: none" @endif wire:ignore>
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <select data-label="{{$parameter->getTranslation('label', 'az')}}" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control parameters" wire:model="logisticsParameters.{{$parameter->name}}">
                                    <option value="" selected>{{$parameter->placeholder}}</option>
                                    @foreach($parameter->getRelationValue('options') as $option)
                                        <option value="{{$option->id}}" data-value="{{$option->getTranslation('text', 'az')}}">{{$option->text}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @break
                    @endswitch
                @endforeach

                @if(!is_null($data) && !is_null(optional($data)->getAttribute('datetime')))
                    <x-input::text wire:ignore name="datetime"  readonly :label="__('translates.fields.date')" value="{{$data->getAttribute('datetime')->format('Y-m-d H:i')}}" width="3" class="pr-3 custom-single-daterange" />
                @endif

                @if(!is_null($data) && !is_null(optional($data)->getAttribute('paid_at')))
                    <x-input::text wire:ignore name="paid_at"  readonly :label="__('translates.columns.paid')" value="{{$data->getAttribute('paid_at')->format('Y-m-d H:i')}}" width="3" class="pr-3" />
                @endif

                @if($method != 'POST')
                    <div class="col-12" wire:ignore>
                        <input type="checkbox" id="data-paid-check" name="paid_check" @if(!is_null(optional($data)->getAttribute('paid_at'))) checked @endif>
                        <label class="form-check-label" for="data-paid-check">@lang('translates.general.paid')</label>
                    </div>
                @endif
                @if($method != 'POST')
                    <div class="col-12" wire:ignore>
                        <input type="checkbox" id="data-datetime-check" name="datetime-check" @if(!is_null(optional($data)->getAttribute('datetime'))) checked @endif>
                        <label class="form-check-label" for="data-datetime-check">@lang('translates.general.done_at')</label>
                    </div>
                @endif

            </div>

        </div>
    </div>
    @if($action)
        <x-input::submit :value="__('translates.buttons.save')"/>
    @endif
</form>


@push('scripts')

@endpush