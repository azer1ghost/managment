<div class="my-5">
    <h4>Nəticə</h4>
    <form action="{{$action}}" method="POST">
        @csrf @method($method)
        <textarea name="content" class="form-control tinyMCE">{{optional($result)->getAttribute('content')}}</textarea>
        @error('content')
        <div class="invalid-feedback p-2">
            {{$message}}
        </div>
        @enderror
        <input type="hidden" name="model" value="{{$model}}">
        @if($status == 'enable')
            <button type="submit" class="btn btn-outline-primary mt-3">@lang('translates.buttons.save')</button>
        @endif
    </form>
</div>
