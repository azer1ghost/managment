<form action="{{$action}}" id="createForm" method="POST" class="tab-content form-row mt-4 mb-5">
    @csrf
    @method($method)

    <x-input::text name="date" :label="__('translates.fields.date')" value="{{$inquiry->datetime->format('d-m-Y')}}" type="text" width="3" class="pr-2" />
    <x-input::text name="time" :label="__('translates.fields.time')" value="{{$inquiry->datetime->format('H:i')}}" type="time" width="3" class="pr-2" />

    <div class="form-group col-md-3">
        <label>{{__('translates.fields.company')}}</label>
        <select class="form-control" name="company_id" required  wire:model="selected.company">
            <option value="null" disabled selected>{{__('translates.fields.company')}} {{__('translates.placeholders.choose')}}</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
    </div>












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