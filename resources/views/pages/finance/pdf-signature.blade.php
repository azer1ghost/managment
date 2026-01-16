@extends('layouts.main')

@section('title', 'PDF İmza Əlavə Et')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">PDF İmza Əlavə Et</h3>
            </div>
            <div class="card-body">
                @if(!$pythonCheck['available'])
                    <div class="alert alert-warning">
                        <h5><i class="fa fa-exclamation-triangle"></i> Xəbərdarlıq</h5>
                        <p class="mb-0">{{ $pythonCheck['message'] }}</p>
                        <hr>
                        <p class="mb-0"><strong>Quraşdırma təlimatları:</strong></p>
                        <pre class="bg-light p-2 mt-2">pip install pymupdf pillow</pre>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="pdf-signature-form" action="{{ route('pdf-signature.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label for="pdf">PDF Faylı Seçin <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" 
                                   class="custom-file-input @error('pdf') is-invalid @enderror" 
                                   id="pdf" 
                                   name="pdf" 
                                   accept=".pdf"
                                   required>
                            <label class="custom-file-label" for="pdf">PDF faylı seçin</label>
                        </div>
                        @error('pdf')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text text-muted">Maksimum ölçü: 20MB</small>
                    </div>

                    <div class="form-group">
                        <label for="signature_name">İmza Seçin <span class="text-danger">*</span></label>
                        <select class="form-control @error('signature_name') is-invalid @enderror" 
                                id="signature_name" 
                                name="signature_name" 
                                required>
                            <option value="">İmza seçin</option>
                            @foreach($signatures as $signature)
                                <option value="{{ $signature }}">{{ $signature }}</option>
                            @endforeach
                        </select>
                        @error('signature_name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                        @if(empty($signatures))
                            <small class="form-text text-danger">
                                Xəbərdarlıq: İmza faylları tapılmadı. Zəhmət olmasa public/assets/images/finance qovluğuna imza faylları əlavə edin.
                            </small>
                        @endif
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                            <i class="fa fa-file-pdf"></i> PDF-ə İmza Əlavə Et
                        </button>
                        <div class="spinner-border text-primary d-none mt-3" id="loading-spinner" role="status">
                            <span class="sr-only">Yüklənir...</span>
                        </div>
                    </div>
                </form>

{{--                <div class="alert alert-info mt-4">--}}
{{--                    <h5><i class="fa fa-info-circle"></i> Məlumat</h5>--}}
{{--                    <ul class="mb-0">--}}
{{--                        <li>İmza PDF-in sol alt hissəsinə yerləşdiriləcək</li>--}}
{{--                        <li>İmza təbii görünməsi üçün kiçik təsadüfi yerdəyişmə və fırlanma əlavə olunur</li>--}}
{{--                        <li>İmzalanmış PDF avtomatik olaraq yüklənəcək</li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Update file input label when file is selected
            $('#pdf').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });

            // Handle form submission
            $('#pdf-signature-form').on('submit', function(e) {
                const submitBtn = $('#submit-btn');
                const spinner = $('#loading-spinner');
                
                // Disable submit button and show loading
                submitBtn.prop('disabled', true);
                spinner.removeClass('d-none');
                
                // Note: For file uploads, we can't use AJAX easily, so the form will submit normally
                // The loading state will be shown until page reloads or error occurs
            });

            // If form has errors, re-enable button
            @if($errors->any())
                $('#submit-btn').prop('disabled', false);
                $('#loading-spinner').addClass('d-none');
            @endif
        });
    </script>
@endsection
