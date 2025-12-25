@extends('pages.transit.layout')

@section('title', __('transit.title') . ' | ' . __('transit.services'))

@section('content')
<div class="transit-card">
    <div class="p-4">
        @auth()
        <div class="d-flex justify-content-end mb-3">
            <a href="{{route('profile.index')}}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-user"></i> {{ __('transit.nav.account') }}
            </a>
        </div>
        @endauth

        <ul class="nav nav-pills nav-justified mb-4" id="serviceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="tab-transit" data-toggle="tab" href="#pills-transit"
                   role="tab" aria-controls="pills-transit" aria-selected="true">
                    <i class="fas fa-truck"></i> {{ __('transit.nav.online_transit') }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-declaration" data-toggle="tab"
                   href="#pills-declaration" role="tab"
                   aria-controls="pills-declaration" aria-selected="false">
                    <i class="fas fa-file-alt"></i> {{ __('transit.nav.short_declaration') }}
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Online Transit Tab -->
            <div class="tab-pane fade show active" id="pills-transit" role="tabpanel"
                 aria-labelledby="tab-transit">
                <form id="transitForm" action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-center mb-5">
                        <div class="mb-3">
                            <i class="fas fa-truck fa-4x text-primary pulse-animation" style="filter: drop-shadow(0 5px 15px rgba(102, 126, 234, 0.5));"></i>
                        </div>
                        <h2 class="mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; font-size: 2.5rem; text-shadow: 0 0 30px rgba(102, 126, 234, 0.3);">
                            WEB TRANSIT
                        </h2>
                        <p class="text-muted fs-5">{{ __('transit.message.upload_documents') }}</p>
                        <div class="progress-bar-wrapper mt-3" style="max-width: 300px; margin: 0 auto;">
                            <div class="progress" style="height: 4px; background: rgba(102, 126, 234, 0.2); border-radius: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: 0%; background: linear-gradient(90deg, #667eea, #764ba2);"
                                     id="uploadProgress"></div>
                            </div>
                        </div>
                    </div>

                    <div id="transitDocuments" class="transit-documents">
                        <div class="document-row mb-4 p-4 border rounded" data-row="0" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border: 2px solid rgba(102, 126, 234, 0.2) !important; transition: all 0.3s ease;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;">
                                    <i class="fas fa-file-alt me-2"></i>{{ __('transit.document.document_set') }} #1
                                </h5>
                                <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 8px 15px; border-radius: 20px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                                    <i class="fas fa-star me-1"></i>{{ __('transit.button.required') }}
                                </span>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" for="cmr_0">
                                        <i class="fas fa-file-pdf text-danger"></i> {{ __('transit.document.cmr') }}
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" name="cmr[]" class="form-control file-input" 
                                               id="cmr_0" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <label for="cmr_0" class="file-upload-label">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <div class="file-name">{{ __('transit.document.choose_file') }}</div>
                                            <small class="text-muted">{{ __('transit.document.file_types') }}</small>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" for="invoice_0">
                                        <i class="fas fa-file-invoice text-success"></i> {{ __('transit.document.invoice') }}
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" name="invoice[]" class="form-control file-input" 
                                               id="invoice_0" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <label for="invoice_0" class="file-upload-label">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <div class="file-name">{{ __('transit.document.choose_file') }}</div>
                                            <small class="text-muted">{{ __('transit.document.file_types') }}</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded" style="background: rgba(102, 126, 234, 0.1); border: 2px dashed rgba(102, 126, 234, 0.3);">
                        <button type="button" class="btn btn-primary" id="addDocumentRow" style="box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);">
                            <i class="fas fa-plus-circle me-2"></i> {{ __('transit.document.add_more') }}
                        </button>
                        <span class="badge badge-lg" id="documentCount" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 10px 20px; border-radius: 25px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-folder-open me-2"></i><span id="countText">1 {{ __('transit.document.document_set') }}</span>
                        </span>
                    </div>

                    <div class="alert alert-info d-none" id="formAlert">
                        <i class="fas fa-info-circle"></i> <span id="alertMessage"></span>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="1" id="transitCheck" required>
                        <label class="form-check-label" for="transitCheck">
                            {{ __('transit.form.terms_agree') }} <a href="#" class="text-primary" data-toggle="modal" data-target="#termsModal">{{ __('transit.form.terms_link') }}</a>
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg pulse-animation" id="submitBtn" style="font-size: 18px; padding: 20px; box-shadow: 0 10px 40px rgba(245, 87, 108, 0.5);">
                            <i class="fas fa-credit-card me-2"></i> {{ __('transit.button.proceed_payment') }}
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-2">{{ __('transit.message.any_questions') }}</p>
                        <a href="tel:+994513339090" class="btn btn-link text-primary">
                            <i class="fas fa-phone"></i> +994 51 333 90 90
                        </a>
                    </div>
                </form>

                <div class="text-center mt-4 pt-4 border-top">
                    <p class="mb-3 text-muted">Follow us on social media</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/mobilbroker.az" target="_blank" 
                           class="btn btn-link btn-floating mx-2 text-primary" title="Facebook">
                            <i class="fab fa-facebook-f fa-2x"></i>
                        </a>
                        <a href="https://www.instagram.com/mobilbroker.az/" target="_blank" 
                           class="btn btn-link btn-floating mx-2 text-danger" title="Instagram">
                            <i class="fab fa-instagram fa-2x"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/mobil-broker-and-logistics-2a1336203/" target="_blank" 
                           class="btn btn-link btn-floating mx-2 text-primary" title="LinkedIn">
                            <i class="fab fa-linkedin fa-2x"></i>
                        </a>
                        <a href="https://www.youtube.com/channel/UCpbkZXCIy4LBkXI0RuF6G8A" target="_blank" 
                           class="btn btn-link btn-floating mx-2 text-danger" title="YouTube">
                            <i class="fab fa-youtube fa-2x"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Short Import Declaration Tab -->
            <div class="tab-pane fade" id="pills-declaration" role="tabpanel"
                 aria-labelledby="tab-declaration">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-file-alt fa-4x text-primary mb-3"></i>
                        <h2 class="mb-3">{{ __('transit.nav.short_declaration') }}</h2>
                    </div>
                    <div class="alert alert-success">
                        <i class="fas fa-clock"></i> {{ __('transit.message.coming_soon') }}
                        <p class="mb-0 mt-2">{{ __('transit.message.will_be_available') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">{{ __('transit.form.terms_link') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('transit.message.accept_terms') }}</p>
                <!-- Add terms content here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('transit.button.back') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let rowCount = 1;
    
    // Translations
    const translations = {
        documentSet: '{{ __('transit.document.document_set') }}',
        documentSets: '{{ __('transit.document.document_sets') }}',
        cmrDocument: '{{ __('transit.document.cmr') }}',
        invoiceDocument: '{{ __('transit.document.invoice') }}',
        chooseFile: '{{ __('transit.document.choose_file') }}',
        fileTypes: '{{ __('transit.document.file_types') }}',
        remove: '{{ __('transit.document.remove') }}',
        required: '{{ __('transit.button.required') }}'
    };

    // File input change handler
    $(document).on('change', '.file-input', function() {
        const input = $(this);
        const label = input.siblings('.file-upload-label');
        const fileName = input[0].files[0]?.name || 'Choose file...';
        const fileSize = input[0].files[0]?.size || 0;
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (fileSize > maxSize) {
            showAlert('File size exceeds 10MB limit. Please choose a smaller file.', 'danger');
            input.val('');
            label.find('.file-name').text('Choose file...');
            label.removeClass('has-file');
            return;
        }

        // Animate file upload
        label.find('.file-name').html('<i class="fas fa-check-circle text-success me-2"></i>' + fileName);
        label.addClass('has-file');
        
        // Add success animation
        label.css('transform', 'scale(1.05)');
        setTimeout(() => {
            label.css('transform', 'scale(1)');
        }, 300);
        
        // Update progress bar
        updateProgressBar();
    });

    // Add new document row
    $('#addDocumentRow').on('click', function() {
        const newRow = `
            <div class="document-row mb-4 p-4 border rounded" data-row="${rowCount}" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border: 2px solid rgba(102, 126, 234, 0.2) !important; opacity: 0; transform: translateY(20px);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;">
                        <i class="fas fa-file-alt me-2"></i>${translations.documentSet} #${rowCount + 1}
                    </h5>
                    <button type="button" class="btn btn-sm btn-danger remove-row" style="box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);">
                        <i class="fas fa-trash me-1"></i> ${translations.remove}
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold" for="cmr_${rowCount}">
                            <i class="fas fa-file-pdf text-danger"></i> ${translations.cmrDocument}
                        </label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="cmr[]" class="form-control file-input" 
                                   id="cmr_${rowCount}" accept=".pdf,.jpg,.jpeg,.png">
                            <label for="cmr_${rowCount}" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <div class="file-name">${translations.chooseFile}</div>
                                <small class="text-muted">${translations.fileTypes}</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold" for="invoice_${rowCount}">
                            <i class="fas fa-file-invoice text-success"></i> ${translations.invoiceDocument}
                        </label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="invoice[]" class="form-control file-input" 
                                   id="invoice_${rowCount}" accept=".pdf,.jpg,.jpeg,.png">
                            <label for="invoice_${rowCount}" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <div class="file-name">${translations.chooseFile}</div>
                                <small class="text-muted">${translations.fileTypes}</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        const $newRow = $(newRow);
        $('#transitDocuments').append($newRow);
        
        // Animate in
        setTimeout(() => {
            $newRow.css({
                'opacity': '1',
                'transform': 'translateY(0)',
                'transition': 'all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)'
            });
        }, 10);
        
        rowCount++;
        updateDocumentCount();
        
        // Button animation
        $(this).css('transform', 'scale(0.95)');
        setTimeout(() => {
            $(this).css('transform', 'scale(1)');
        }, 200);
    });

    // Remove document row
    $(document).on('click', '.remove-row', function() {
        $(this).closest('.document-row').fadeOut(300, function() {
            $(this).remove();
            updateDocumentCount();
        });
    });

    // Update document count
    function updateDocumentCount() {
        const count = $('.document-row').length;
        $('#countText').text(count + ' ' + (count > 1 ? translations.documentSets : translations.documentSet));
        
        // Animate badge
        $('#documentCount').css('transform', 'scale(1.1)');
        setTimeout(() => {
            $('#documentCount').css('transform', 'scale(1)');
        }, 200);
    }
    
    // Update progress bar
    function updateProgressBar() {
        const totalInputs = $('.file-input').length;
        const filledInputs = $('.file-input').filter(function() {
            return $(this).val() !== '';
        }).length;
        const percentage = (filledInputs / (totalInputs * 2)) * 100; // Each row has 2 inputs
        
        $('#uploadProgress').css('width', percentage + '%');
        
        if (percentage === 100) {
            $('#uploadProgress').addClass('glow');
        }
    }

    // Show alert
    function showAlert(message, type = 'info') {
        const alert = $('#formAlert');
        alert.removeClass('d-none alert-info alert-danger alert-success')
             .addClass('alert-' + type)
             .css({
                 'opacity': '0',
                 'transform': 'translateY(-20px)'
             });
        $('#alertMessage').html('<i class="fas fa-' + (type === 'danger' ? 'exclamation-triangle' : 'info-circle') + ' me-2"></i>' + message);
        
        setTimeout(() => {
            alert.css({
                'opacity': '1',
                'transform': 'translateY(0)',
                'transition': 'all 0.3s ease'
            });
        }, 10);
        
        setTimeout(() => {
            alert.css({
                'opacity': '0',
                'transform': 'translateY(-20px)'
            });
            setTimeout(() => alert.addClass('d-none'), 300);
        }, 5000);
    }

    // Form validation
    $('#transitForm').on('submit', function(e) {
        if (!$('#transitCheck').is(':checked')) {
            e.preventDefault();
            showAlert('{{ __('transit.message.accept_terms') }}', 'danger');
            $('#transitCheck').focus();
            return false;
        }

        const hasFiles = $('.file-input').filter(function() {
            return $(this).val() !== '';
        }).length > 0;

        if (!hasFiles) {
            e.preventDefault();
            showAlert('{{ __('transit.message.upload_required') }}', 'danger');
            return false;
        }

        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> {{ __('transit.status.processing') }}...');
    });
});
</script>

<style>
.document-row {
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    position: relative;
    overflow: hidden;
}
.document-row::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
    transition: left 0.5s;
}
.document-row:hover::before {
    left: 100%;
}
.document-row:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%) !important;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3) !important;
    transform: translateY(-5px) scale(1.02);
    border-color: rgba(102, 126, 234, 0.5) !important;
}
.file-upload-wrapper {
    position: relative;
}
.file-upload-wrapper input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    z-index: 2;
}
.file-upload-label {
    display: block;
    padding: 20px;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.file-upload-label:hover {
    background: #e9ecef;
    border-color: #667eea;
}
.file-upload-label.has-file {
    background: #d4edda;
    border-color: #28a745;
}
.file-name {
    font-weight: 500;
    margin-top: 10px;
}
.social-links a {
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    display: inline-block;
}
.social-links a:hover {
    transform: translateY(-10px) scale(1.2) rotate(5deg);
    filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
}
.progress-bar-wrapper {
    animation: fadeInUp 0.6s ease-out;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
