<form action="{{$action}}" method="POST" enctype="multipart/form-data">
    @method($method) @csrf

    <div class="tab-content row mt-4">
        <div class="form-group col-12">
            <div class="row">
                <x-input::text name="name" :value="optional($data)->getAttribute('name')" label="Work name" width="6" class="pr-3"/>
                <x-input::textarea name="detail" :value="optional($data)->getAttribute('detail')" label="Work detail" width="6" class="pr-3"/>

                <div class="form-group col-12 col-md-6">
                    <label for="data-service_id">Service Select</label>
                    <select name="service_id" id="data-service_id" class="form-control" wire:model="selected.service_id">
                        <option value="" selected>Service Select</option>
                        @foreach($services as $service)
                            <option value="{{$service->getAttribute('id')}}">{{$service->getAttribute('name')}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="data-company_id">Company Select</label>
                    <select name="company_id" id="data-company_id" class="form-control" wire:model="selected.company_id">
                        <option value="" selected>Company Select</option>
                        @foreach($companies as $company)
                            <option value="{{$company->getAttribute('id')}}">{{$company->getAttribute('name')}}</option>
                        @endforeach
                    </select>
                </div>

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
                        <option value="null">Select client</option>
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