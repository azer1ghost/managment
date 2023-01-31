<style>
    img {
        width: 250px;
    }
</style>

<div class="container">
    <div class="card my-5">
        <div class="card-header text-center" style="background-color: greenyellow">
            <h3 class="mt-2">{{\App\Models\Satisfaction::where('url',$this->selected['url'])->first()->getRelationValue('company')->getAttribute('name')}}</h3>
        </div>
        <div class="card-body">

            <div class="col-12">
                <div class="text-center m-4">
                    <img src="{{asset("assets/images/".\App\Models\Satisfaction::where('url',$this->selected['url'])->first()->getRelationValue('company')->getAttribute('logo'))}}" alt="logo">
                </div>
                <form action="{{$action}}" method="POST" enctype="multipart/form-data" id="work-form">
                    @method($method) @csrf

                    <div wire:loading.delay class="col-12 mr-2" style="position: absolute;right:20px">
                        <div style="position: absolute;right: 0;top: -25px">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                    <div>
                        <div>
                            <div class="px-lg-5 my-5">
                                <div class="text-center my-5"><h4>@lang('translates.customer_satisfaction.rate')</h4></div>
                                <input type="hidden" id="rate-input" value="{{optional($data)->getAttribute('rate')}}" name="rate" wire:ignore>
                                <div class="mt-4 d-flex justify-content-between">
                                    <span class="rate px-1" id="1" wire:ignore><i class="fal fa-frown-open fa-3x"></i></span>
                                    <span class="rate px-1" id="2" wire:ignore><i class="fal fa-frown fa-3x"></i></span>
                                    <span class="rate px-1" id="3" wire:ignore><i class="fal fa-meh fa-3x"></i></span>
                                    <span class="rate px-1" id="4" wire:ignore><i class="fal fa-smile fa-3x"></i></span>
                                    <span class="rate px-1" id="5" wire:ignore><i class="fal fa-laugh-squint fa-3x"></i></span>
                                </div>
                                <hr>
                            </div>
                            <div class="px-lg-5 my-5">
                                <div class="text-center my-5"><h4>@lang('translates.customer_satisfaction.price_rate')</h4></div>
                                <input type="hidden" id="price-rate-input" value="{{optional($data)->getAttribute('price_rate')}}" name="price_rate" wire:ignore>
                                <div class="mt-4 d-flex justify-content-between">
                                    <span class="price-rate px-1" id="1" wire:ignore><i class="fal fa-frown-open fa-3x"></i></span>
                                    <span class="price-rate px-1" id="2" wire:ignore><i class="fal fa-frown fa-3x"></i></span>
                                    <span class="price-rate px-1" id="3" wire:ignore><i class="fal fa-meh fa-3x"></i></span>
                                    <span class="price-rate px-1" id="4" wire:ignore><i class="fal fa-smile fa-3x"></i></span>
                                    <span class="price-rate px-1" id="5" wire:ignore><i class="fal fa-laugh-squint fa-3x"></i></span>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="row px-lg-5">
                            <div class="form-group" wire:ignore>
                                <input wire:ignore type="hidden" name="satisfaction_id" class="form-control"
                                   value="{{\App\Models\Satisfaction::where('url', $this->selected['url'])->first()->id}}">
                            </div>
                            @foreach($parameters as $parameter)
                                @switch($parameter->type)
                                    @case('text')
                                        <div class="form-group col-12 text-center" wire:ignore>
                                            <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                            <input type="text"
                                                   data-label="{{$parameter->getTranslation('label', 'az')}}"
                                                   name="parameters[{{$parameter->id}}]"
                                                   {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}"
                                                   class="form-control parameters parameters[{{$parameter->id}}]"
                                                   placeholder="{{$parameter->placeholder}}"
                                                   wire:model="customerSatisfactionParameters.{{$parameter->name}}">
                                        </div>
                                        @break
                                    @case('number')
                                        <div class="form-group col-12 text-center" wire:ignore>
                                            <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                            <input type="number"
                                                   data-label="{{$parameter->getTranslation('label', 'az')}}"
                                                   name="parameters[{{$parameter->id}}]"
                                                   {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}"
                                                   class="form-control parameters"
                                                   placeholder="{{$parameter->placeholder}}"
                                                   wire:model="customerSatisfactionParameters.{{$parameter->name}}">
                                        </div>
                                        @break
                                    @case('select')
                                        <div class="form-group col-12 text-center" wire:ignore>
                                            <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                            <select data-label="{{$parameter->getTranslation('label', 'az')}}"
                                                    name="parameters[{{$parameter->id}}]"
                                                    {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}"
                                                    class="form-control parameters"
                                                    wire:model="customerSatisfactionParameters.{{$parameter->name}}">
                                                <option value="" selected>{{$parameter->placeholder}}</option>
                                                @foreach($parameter->getRelationValue('options') as $option)
                                                    <option value="{{$option->getTranslation('text', 'az')}}"
                                                            data-value="{{$option->getTranslation('text', 'az')}}">{{$option->text}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @break
                                @endswitch
                            @endforeach
                            <div class="form-group col-12 text-center" wire:ignore>
                                <label style="font-weight: 20; display: block" for="data-note">@lang('translates.customer_satisfaction.note')</label>
                                <textarea style="border: 1px solid black;" class="form-control" name="note" id="data-note" placeholder="@lang('translates.placeholders.comment')" cols="30" rows="10">{{optional($data)->getAttribute('note')}}</textarea>
                            </div>
                        </div>
                    </div>

                    @if($action)
                        <x-input::submit class="text-center" :value="__('translates.buttons.send')"/>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.rate').forEach(occurence => {
        occurence.addEventListener('click', () => {
            document.querySelectorAll('.rate').forEach(occurence => {
                occurence.style.color = "black"
            })
            document.querySelector('#rate-input').value = occurence.getAttribute('id');
            occurence.style.color = "green"
        })
    });

    document.querySelectorAll('.price-rate').forEach(occurence => {
        occurence.addEventListener('click', () => {
            document.querySelectorAll('.price-rate').forEach(occurence => {
                occurence.style.color = "black"
            })
            document.querySelector('#price-rate-input').value = occurence.getAttribute('id');
            occurence.style.color = "green"
        })
    });
</script>
 <script>
    $('#work-form :input').attr('disabled', true)
</script>