<div class="my-5">
    <h4>Result</h4>
    <form action="{{$action}}" method="POST">
        @csrf @method($method)
        <textarea name="content" id="summernote" class="form-control">{{optional($result)->getAttribute('content')}}</textarea>
        <input type="hidden" name="model" value="{{$model}}">
        @if($status == 'enable')
            <button type="submit" class="btn btn-outline-primary mt-3">@lang('translates.buttons.save')</button>
        @endif
    </form>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            const summernote = $('#summernote');
            summernote.summernote({
                placeholder: 'Results',
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear', 'italic']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
            summernote.summernote('{{$status}}');
        });
    </script>
@endpush