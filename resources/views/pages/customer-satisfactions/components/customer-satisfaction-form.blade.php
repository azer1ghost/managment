<div class="container">
    <div class="card text-center my-5 mx-5">
        <div class="card-header" style="background-color: greenyellow">Mobil Technologies</div>
        <div class="card-body">
            <div class="row">
                <div class="row">
                    <div class="center-block">
                        <span></span>
                    </div>
                </div>
                <div class="col-12">
                    <form action="{{$action}}" method="POST" enctype="multipart/form-data" id="work-form">
                        @method($method) @csrf
                        <div wire:loading.delay class="col-12 mr-2" style="position: absolute;right:20px">
                            <div style="position: absolute;right: 0;top: -25px">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <div class="row">

                                    <div class="form-group" wire:ignore>
                                        <input wire:ignore type="hidden" name="satisfaction_id" class="form-control"
                                               value="{{\App\Models\Satisfaction::where('url',$this->selected['url'])->first()->id}}">
                                    </div>

                                    @foreach($parameters as $parameter)
                                        @if(in_array('hideOnPost', explode(' ', $parameter->attributes)) && $method == 'POST')
                                            @continue
                                        @endif
                                        @switch($parameter->type)
                                            @case('text')
                                                <div class="form-group col-12" wire:ignore>
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
                                                <div class="form-group col-12" wire:ignore>
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
                                                <div class="form-group col-12" wire:ignore>
                                                    <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                                    <select data-label="{{$parameter->getTranslation('label', 'az')}}"
                                                            name="parameters[{{$parameter->id}}]"
                                                            {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}"
                                                            class="form-control parameters"
                                                            wire:model="customerSatisfactionParameters.{{$parameter->name}}">
                                                        <option value="" selected>{{$parameter->placeholder}}</option>
                                                        @foreach($parameter->getRelationValue('options') as $option)
                                                            <option value="{{$option->id}}"
                                                                    data-value="{{$option->getTranslation('text', 'az')}}">{{$option->text}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @break
                                        @endswitch
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if($action)
                            <x-input::submit :value="__('translates.buttons.save')"/>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
