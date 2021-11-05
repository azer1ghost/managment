@push('style')
    <!-- include summernote css -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush
<div class="my-5">
    <h4>Result</h4>
    <form action="{{$action}}" method="POST">
        @csrf @method($method)
        <textarea name="content" id="summernote" class="form-control">{{optional($result)->getAttribute('content')}}</textarea>
        <input type="hidden" name="model" value="{{$model}}">
        <button type="submit" class="btn btn-outline-primary mt-3">@lang('translates.buttons.save')</button>
    </form>
</div>
@push('scripts')
    <!-- include summernote css/js -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Results',
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endpush