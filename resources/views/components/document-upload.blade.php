<div>
    <form id="document-form" action="{{route('documents.store', $id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <div class="custom-file" style="width: 350px !important;max-width: 100%">
                <input type="file" name="file" id="document-file" class="custom-file-input" required>
                <label class="custom-file-label" for="document-file">Choose file</label>
            </div>
            <input type="hidden" name="model" value="{{$model}}">
            @error('file')
                <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="d-flex align-items-center">
            <button type="submit" id="document-form-submit" class="btn btn-outline-primary mr-3">Upload file</button>
            <div class="spinner-border text-primary d-none" id="document-form-btn" style="width: 1.5rem !important;height: 1.5rem !important;" role="status"></div>
        </div>
    </form>
</div>
@push('scripts')
    <script>
        $('#document-form').submit(function (){
            $('#document-form-btn').removeClass('d-none');
            $('#document-form-submit').prop('disabled', true);
            $('#document-file').prop('readonly', true);
        });

        // Add the following code if you want the name of the file appear on select
        $("#document-file").on("change", function() {
            const fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
@endpush