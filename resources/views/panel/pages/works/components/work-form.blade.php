<form action="{{$action}}" method="POST" enctype="multipart/form-data">
    @method($method) @csrf

    <div class="tab-content row mt-4">
        <div class="form-group col-12">
            <div class="row">
                <div class="form-group col-12 col-md-6" wire:ignore>
                    <div class="d-flex">
                        <div class="btn-group mr-5 flex-column" role="group">
                            <label for="data-earning">@lang('translates.general.work_earning')</label>
                            <div class="d-flex">
                                <input id="data-earning" type="number" min="0" class="form-control" name="earning" wire:model="earning" style="border-radius: 0 !important;">
                                <select name="currency" id="" class="form-control" style="border-radius: 0 !important;" wire:model="currency">
                                    @foreach(['USD', 'AZN', 'TRY', 'EUR', 'RUB'] as $currency)
                                        <option value="{{$currency}}" @if($currency == optional($data)->getAttribute('currency')) selected @endif>{{$currency}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('earning')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="btn-group flex-column" role="group">
                            <label for="data-earning">@lang('translates.general.work_rate')</label>
                            <div class="d-flex">
                                <input type="text" class="form-control" name="currency_rate" wire:model="rate" style="border-radius: 0 !important;">
                                <input disabled type="text" class="form-control" value="AZN" style="border-radius: 0 !important;">
                            </div>
                            @error('currency_rate')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <x-input::textarea name="detail" :value="optional($data)->getAttribute('detail')" :label="trans('translates.general.work_detail')" width="6" class="pr-3"/>

                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-service_id">@lang('translates.general.work_service')</label>
                    @if(request()->has('service_id') || !is_null($data))
                        @php($service = request()->get('service_id') ?? optional($data)->getAttribute('service_id'))
                        <input disabled type="text" class="form-control" id="data-service_id" value="{{\App\Models\Service::find($service)->name}}">
                        <input type="hidden" name="service_id"  value="{{$service}}">
                    @endif
                </div>

                @foreach($parameters as $parameter)
                    @switch($parameter->type)
                        @case('text')
                            <div class="form-group col-12 col-md-6">
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <input type="text" name="parameters[{{$parameter->id}}]" id="data-parameter-{{$parameter->id}}" class="form-control" placeholder="{{$parameter->placeholder}}" wire:model="workParameters.{{$parameter->name}}">
                            </div>
                            @break
                        @case('select')
                            <div class="form-group col-12 col-md-6">
                                <label for="data-parameter-{{$parameter->id}}">@lang('translates.navbar.work') {{$parameter->name}}</label>
                                <select name="parameters[{{$parameter->id}}]" id="data-parameter-{{$parameter->id}}" class="form-control" wire:model="workParameters.{{$parameter->name}}">
                                    <option value="" selected>@lang('translates.general.work_detail') {{$parameter->name}}</option>
                                    @foreach($parameter->options as $option)
                                        <option value="{{$option->id}}">{{$option->text}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @break
                    @endswitch
                @endforeach

                <div class="form-group col-12 col-md-6">
                    <label for="data-department_id">@lang('translates.general.department_select')</label>
                    <select name="department_id" id="data-department_id" class="form-control" wire:model="selected.department_id">
                        <option value="" selected>@lang('translates.general.department_select')</option>
                        @foreach($departments as $department)
                            <option value="{{$department->getAttribute('id')}}">{{$department->getAttribute('name')}}</option>
                        @endforeach
                    </select>
                </div>

                @if($selected['department_id'])
                    <div class="form-group col-12 col-md-6">
                        <label for="data-user_id">User Select</label>
                        <select name="user_id" id="data-user_id" class="form-control" wire:model="selected.user_id">
                            <option value="" selected>User Select</option>
                            @foreach($this->department->users()->isActive()->get(['id', 'name', 'surname']) as $user)
                                <option value="{{ $user->getAttribute('id') }}">{{ $user->getAttribute('fullname') }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif


                <div class="form-group col-12 col-md-6">
                    <label for="data-client-type">{{trans('translates.fields.clientName')}}</label><br/>
                    <select name="client_id" class="form-control" style="border-radius: 0 !important;" wire:model="selected.client_id">
                        <option value="">@lang('translates.general.select_client')</option>
                        @foreach($clients as $client)
                            <option value="{{$client->getAttribute('id')}}">{{$client->getAttribute('fullname')}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    @if($action)
        <x-input::submit :value="__('translates.buttons.save')"/>
    @endif
</form>
@push('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endpush