<div>
    @if($errors->any())
        <ul>
            {!! implode('', $errors->all('<li class="text-danger">:message</li>')) !!}
        </ul>
    @endif
    <div class="row">
        @if($socials)
            @foreach($socials as $index => $social)
                <div wire:key="social-{{$index}}" class="col-12">
                    <hr class="m-1">
                    <div class="row d-flex align-items-center">
                        <input type="hidden"  name="socials[{{$index}}][id]"    value="{{$social['id']}}">
                        <x-input::select      name="socials[{{$index}}][name]" :value="$social['name']"  label="Social name" :width="5" :options="$socialNetworks"/>
                        <x-input::text        name="socials[{{$index}}][url]"  :value="$social['url']"   label="Social url"  width="6"  class="pr-3"/>
                        @if (!$social['id'])
                            <div class="form-group col-12 col-md-1 mb-3 mb-md-0 mt-0 mt-md-3 pl-3 pl-md-0">
                                <button type="button" wire:click.prevent="removeSocial({{$index}})" class="btn btn-outline-danger"><i class='fal fa-times'></i></button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    @if($action)
        <x-input::submit wire:click.prevent="addSocial" value="<i class='fal fa-plus'></i>" type="button" color="success" layout="left" class="d-inline pl-0" width="1"/>
    @endif
</>
