<form action="{{$action}}" id="createForm" method="POST" class="tab-content form-row mt-4 mb-5">
    @csrf
    @method($method)
    <input type="hidden" name="code" value="{{optional($data)->code}}">

    <x-input::select name="contact_method" label="Contact method" value="{{optional($data)->contact_method}}" :options="$contact_methods" width="3" class="pr-3" />

    <x-input::text name="date" value="{{(optional($data)->datetime ?? now())->format('d-m-Y')}}" type="text" width="3" class="pr-2" />
    <x-input::text name="time" value="{{(optional($data)->datetime ?? now())->format('H:m')}}" type="time" width="3" class="pr-2" />

    <div class="form-group col-6 col-md-3">
        <label for="company" >Company</label>
        <select wire:model="selectedCompany" id="company" name="company_id" required class="form-control @error('company_id') is-invalid @enderror">
            <option value="null" disabled  selected>Choose company</option>
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

    @if ($selectedCompany === "4" || optional($data)->company_id === 4)
        <x-input::text name="client" width="3" value="{{optional($data)->client}}" placeholder="MBX" class="pr-2" />
    @endif

    <x-input::text name="fullname" value="{{optional($data)->fullname}}" width="3" class="pr-2" />

    <x-input::text name="phone" value="{{optional($data)->phone}}" width="3" class="pr-2" />

    @if ($subjects->isNotEmpty())
        <div class="form-group col-6 col-md-3">
            <label for="subject" >Subject</label>
            <select wire:model="selectedSubject" id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror">
                <option value="null" disabled selected>Choose subject</option>
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
            <label for="kind" >Kind</label>
            <select wire:model="selectedKind" class="form-control" id="kind" name="kind">
                <option value="null" disabled selected>Choose kind</option>
                @foreach($kinds as $kind)
                    <option value="{{ $kind->id }}">{{ $kind->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    @if ($sources->isNotEmpty())
        <x-input::select name="source" value="{{optional($data)->source}}" :options="$sources->toArray()" width="3" class="pr-3" />
    @endif

    <x-input::textarea name="note" :value="optional($data)->note"/>

    <x-input::select name="status" value="{{optional($data)->status}}" :options="$statuses" width="3" class="pr-3" />

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