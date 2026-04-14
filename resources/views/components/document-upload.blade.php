<div>
    <form id="document-form" class="form-row" action="{{route('documents.store', $id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="input-group col-12 col-md-6 @error('files.*') is-invalid @enderror">
            <div class="custom-file" style="width: 350px !important;max-width: 100%">
                <input type="file" name="files[]" id="document-file" class="custom-file-input" multiple required>
                <label class="custom-file-label" for="document-file">@lang('translates.placeholders.choose_file')</label>
            </div>
            <div class="input-group-append">
                <button type="submit" id="document-form-submit" class="btn btn-outline-primary mr-3">@lang('translates.buttons.upload_file')</button>
                <div class="spinner-border text-primary d-none" id="document-form-btn"></div>
            </div>
            <input type="hidden" name="model" value="{{$model}}">
        </div>
        @error('files.*')
        <div class="invalid-feedback p-2">
            {{$message}}
        </div>
        @enderror
    </form>
</div>
@push('scripts')
    <script>
        $('#document-form').submit(function (){
            $('#document-form-btn').removeClass('d-none');
            $('#document-form-submit').prop('disabled', true);
            $('#document-file').prop('readonly', true);
        });

        $("#document-file").on("change", function() {
            const files = this.files;
            if (files.length > 1) {
                $(this).siblings(".custom-file-label").addClass("selected").html(files.length + ' fayl seçildi');
            } else if (files.length === 1) {
                $(this).siblings(".custom-file-label").addClass("selected").html(files[0].name);
            }
        });
    </script>
@endpush