<div class="my-5">
    <h4>Result</h4>
    <form action="{{$action}}" method="POST">
        @csrf @method($method)
        <textarea name="content" class="form-control tinyMCE">{{optional($result)->getAttribute('content')}}</textarea>
        <input type="hidden" name="model" value="{{$model}}">
        @if($status == 'enable')
            <button type="submit" class="btn btn-outline-primary mt-3">@lang('translates.buttons.save')</button>
        @endif
    </form>
</div>
