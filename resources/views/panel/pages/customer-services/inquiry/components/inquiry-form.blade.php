<form action="{{$action}}" id="createForm" method="POST" class="tab-content form-row mt-4 mb-5">
    @csrf
    @method($method)

    <x-input::select name="contact_method" :label="__('translates.fields.contactMethod')" value="{{$inquiry->contact_method}}" :options="$contact_methods" width="3" class="pr-3" />

{{--    <x-input::text name="date" :label="__('translates.fields.date')" value="{{$inquiry->datetime->format('d-m-Y')}}" type="text" width="3" class="pr-2" />--}}
{{--    <x-input::text name="time" :label="__('translates.fields.time')" value="{{$inquiry->datetime->format('H:i')}}" type="time" width="3" class="pr-2" />--}}

    <div class="form-group col-6 col-md-3">
        <label for="company" >{{__('translates.fields.company')}}</label>
        <select wire:model="selectedCompany" id="company" name="company_id" required class="form-control @error('company_id') is-invalid @enderror">
            <option value="null" disabled selected>{{__('translates.fields.company')}} {{__('translates.placeholders.choose')}}</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
        @error('company_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    @if ($selectedCompany === 4 || optional($data)->company_id === 4)
        <x-input::text name="client" :label="__('translates.fields.client')" width="3" value="{{$inquiry->client}}" placeholder="MBX" class="pr-2" />
    @endif

    <x-input::text name="fullname" :label="__('translates.fields.fullname')" :placeholder="__('translates.placeholders.fullname')" value="{{$inquiry->fullname}}" width="3" class="pr-2" />

    <x-input::text name="phone" :label="__('translates.fields.phone')" :placeholder="__('translates.placeholders.phone')" value="{{$inquiry->phone}}" width="3" class="pr-2" />

    @if ($subjects->isNotEmpty())
        <div class="form-group col-6 col-md-3">
            <label for="subject" >{{__('translates.parameters.types.subject')}}</label>
            <select wire:model="selectedSubject" id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror">
                <option value="null" disabled selected>{{__('translates.parameters.types.subject')}} {{__('translates.placeholders.choose')}}</option>
                @foreach($subjects as $id => $subject)
                    <option value="{{$id}}">{{ $subject }}</option>
                @endforeach
            </select>
            @error('subject')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    @endif

    @if ($kinds->isNotEmpty())
        <div class="form-group col-6 col-md-3">
            <label for="kind" >{{__('translates.parameters.types.kind')}}</label>
            <select wire:model="selectedKind" class="form-control" id="kind" name="kind">
                <option value="null" disabled selected>{{__('translates.parameters.types.kind')}} {{__('translates.placeholders.choose')}}</option>
                @foreach($kinds as $kind)
                    <option value="{{ $kind->id }}">{{ $kind->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    @if ($sources->isNotEmpty())
        <x-input::select name="source" :label="__('translates.parameters.types.source')" value="{{optional($data)->source}}" :options="$sources->toArray()" width="3" class="pr-3" />
    @endif

    <x-input::textarea :label="__('translates.fields.note')" :placeholder="__('translates.placeholders.note')" name="note" :value="optional($data)->note"/>

    <x-input::select name="status" :label="__('translates.parameters.types.status')" value="{{optional($data)->status}}" :options="$statuses" width="3" class="pr-3" />

    @if($action)
    <div class="col-12">
        <button class="btn btn-outline-primary float-right">Save</button>
    </div>
    @endif
</form>

@if(is_null($action))
@section('scripts')
    <script>
        $('input').attr('readonly', true)
        $('select').attr('disabled', true)
        $('textarea').attr('readonly', true)
    </script>
@endsection
@endif


{{--    <x-input::select name="redirected" :options="$operators" label="Redirect" width="4" class="pr-2" />--}}