<div>
    <div class="row">
        @forelse($salesSupplies as $index => $supply)
            <div class="col-12">
                <hr class="m-1">
                <div class="row d-flex align-items-center">
                    <input type="hidden"  name="supplies[{{$index}}][id]"    value="{{$supply['id']}}">
                    <x-input::text  name="supplies[{{$index}}][name]" :value="$supply['name']"   label="Supply name"   width="5"/>
                    <x-input::text  name="supplies[{{$index}}][value]"  :value="$supply['value']"  label="Supply value (AZN)"  width="5"  class="pr-3"/>
                    @if($action)
                        <div class="form-group col-12 col-md-1 mb-3 mb-md-0 mt-0 mt-md-3 pl-3 pl-md-0">
                            <button type="button" wire:click.prevent="removeSupply({{$index}})" class="btn btn-outline-danger">
                                <i class='fal fa-times'></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">No Sales Supplies</p>
            </div>
        @endforelse



    </div>
    @if($action)
        <x-input::submit wire:click.prevent="addSupply" value="<i class='fal fa-plus'></i>" type="button" color="success" layout="left" class="d-inline pl-0" width="1"/>
    @endif
</div>
