<div class="my-5">
    <div class="row">
        @forelse($serviceParameters as $index => $parameter)
            <div class="col-12">
                <hr class="m-1">
                <div class="row d-flex align-items-center">
                    <x-input::select      name="parameters[{{$index}}]" label="Parameter" :value="$parameter['id']"  width="5" :options="$parameters"/>
                    @if($action)
                        <div class="form-group col-12 col-md-1 mb-3 mb-md-0 mt-0 mt-md-3 pl-3 pl-md-0">
                            <button type="button" wire:click.prevent="removeParameter({{$index}})" class="btn btn-outline-danger">
                                <i class='fal fa-times'></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">@lang('translates.general.no_service_parameter')</p>
            </div>
        @endforelse
    </div>
    @if($action)
        <x-input::submit wire:click.prevent="addParameter" value="<i class='fal fa-plus'></i>" type="button" color="success" layout="left" class="d-inline pl-0" width="1"/>
    @endif
</div>
