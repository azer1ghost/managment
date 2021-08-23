<div>
    @if($errors->any())
        <ul>
            {!! implode('', $errors->all('<li class="text-danger">:message</li>')) !!}
        </ul>
    @endif
    <div class="row">
            @forelse($defaults as $index => $default)
                <div class="col-12">
                    <hr class="m-1">
                    <div class="row d-flex align-items-center">
                        <input type="hidden"  name="defaults[{{$index}}][id]"   value="{{$default['id']}}">
                        <div class="form-group col-md-5">
                            <label>Default column</label>
                            <select class="form-control" name="defaults[{{$index}}][parameter_id]" required wire:click="changeOptions($event.target.value)">
                                <option value="null" disabled selected>Choose Column</option>
                                @foreach($columns as $key => $column)
                                    <option @if ($key === $default['parameter_id']) selected @endif value="{{$key}}">{{$column}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <label>Default value</label>
                            <select class="form-control" name="defaults[{{$index}}][option_id]" required>
                                <option value="null" disabled selected>Choose Value</option>
                                @foreach($values as $key => $value)
                                    <option @if ($key === $default['option_id']) selected @endif value="{{$key}}">{{ $value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-2 mb-3 mb-md-0 mt-0 mt-md-3 pl-3 pl-md-0">
                            <button type="button" wire:click.prevent="removeDefault({{$index}})" class="btn btn-outline-danger">
                                <i class='fal fa-times'></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">No User Defaults</p>
                </div>
            @endforelse
    </div>
        <x-input::submit wire:click.prevent="addDefault" value="<i class='fal fa-plus'></i>" type="button" color="success" layout="left" class="d-inline pl-0" width="1"/>
</div>
