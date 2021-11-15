<form action="{{$action}}" method="POST" enctype="multipart/form-data">
    @method($method) @csrf

    <div class="tab-content row mt-4">
        <div class="form-group col-12">
            <div class="row">
                <x-input::text name="name" :value="optional($data)->getAttribute('name')" label="Work name" width="6" class="pr-3"/>
                <x-input::textarea name="detail" :value="optional($data)->getAttribute('detail')" label="Work detail" width="6" class="pr-3"/>

                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-service_id">Work Service</label>
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
                                <label for="data-parameter-{{$parameter->id}}">Work {{$parameter->name}}</label>
                                <select name="parameters[{{$parameter->id}}]" id="data-parameter-{{$parameter->id}}" class="form-control" wire:model="workParameters.{{$parameter->name}}">
                                    <option value="" selected>Select work {{$parameter->name}}</option>
                                    @foreach($parameter->options as $option)
                                        <option value="{{$option->id}}">{{$option->text}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @break
                    @endswitch
                @endforeach

                <div class="form-group col-12 col-md-6">
                    <label for="data-department_id">Department Select</label>
                    <select name="department_id" id="data-department_id" class="form-control" wire:model="selected.department_id">
                        <option value="" selected>Department Select</option>
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
                        <option value="">Select client</option>
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