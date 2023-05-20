<div>
    <div class="row">
        @forelse($evaluations as $index => $evaluation)
            <div class="col-12">
                <hr class="m-1">
                <div class="row d-flex align-items-center">
                    <input type="hidden"  name="evaluations[{{$index}}][supplier_id]"    value="{{$evaluation['supplier_id']}}">
                    <x-input::text  name="evaluations[{{$index}}][quality]" :value="$evaluation['quality']"   :label="trans('translates.sales_supply.supply_name')"   width="5"/>
                    <x-input::text  name="evaluations[{{$index}}][delivery]"  :value="$evaluation['delivery']"  :label="trans('translates.sales_supply.supply_value')"  width="5"  class="pr-3"/>
                    <x-input::text  name="evaluations[{{$index}}][distributor]"  :value="$evaluation['distributor']"  :label="trans('translates.sales_supply.supply_value')"  width="5"  class="pr-3"/>
                    <x-input::text  name="evaluations[{{$index}}][availability]"  :value="$evaluation['availability']"  :label="trans('translates.sales_supply.supply_value')"  width="5"  class="pr-3"/>
                    <x-input::text  name="evaluations[{{$index}}][certificate]"  :value="$evaluation['certificate']"  :label="trans('translates.sales_supply.supply_value')"  width="5"  class="pr-3"/>
                    <x-input::text  name="evaluations[{{$index}}][support]"  :value="$evaluation['support']"  :label="trans('translates.sales_supply.supply_value')"  width="5"  class="pr-3"/>
                    <x-input::text  name="evaluations[{{$index}}][price]"  :value="$evaluation['price']"  :label="trans('translates.sales_supply.supply_value')"  width="5"  class="pr-3"/>
                    <x-input::text  name="evaluations[{{$index}}][payment]"  :value="$evaluation['payment']"  :label="trans('translates.sales_supply.supply_value')"  width="5"  class="pr-3"/>
                    <x-input::text  name="evaluations[{{$index}}][returning]"  :value="$evaluation['returning']"  :label="trans('translates.sales_supply.supply_value')"  width="5"  class="pr-3"/>
                    <x-input::text  name="evaluations[{{$index}}][replacement]"  :value="$evaluation['replacement']"  :label="trans('translates.sales_supply.supply_value')"  width="5"  class="pr-3"/>
                    @if($action)
                        <div class="form-group col-12 col-md-1 mb-3 mb-md-0 mt-0 mt-md-3 pl-3 pl-md-0">
                            <button type="button" wire:click.prevent="removeEvaluation({{$index}})" class="btn btn-outline-danger">
                                <i class='fal fa-times'></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">No Evaluation</p>
            </div>
        @endforelse
    </div>
    @if($action)
        <x-input::submit wire:click.prevent="addEvaluation" value="<i class='fal fa-plus'></i>" type="button" color="success" layout="left" class="d-inline pl-0" width="1"/>
    @endif
</div>
