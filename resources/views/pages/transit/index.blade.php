@extends('pages.transit.layout')

@section('title', 'Online Transit | Services')

@section('content')
<div class="transit-card">
    <div class="p-4">
        @auth()
        <div class="d-flex justify-content-end mb-3">
            <a href="{{route('profile.index')}}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-user"></i> Account
            </a>
        </div>
        @endauth

        <ul class="nav nav-pills nav-justified mb-4" id="serviceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="tab-transit" data-toggle="tab" href="#pills-transit"
                   role="tab" aria-controls="pills-transit" aria-selected="true">
                    <i class="fas fa-truck"></i> Online Transit
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-declaration" data-toggle="tab"
                   href="#pills-declaration" role="tab"
                   aria-controls="pills-declaration" aria-selected="false">
                    <i class="fas fa-file-alt"></i> Short Import Declaration
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Online Transit Tab -->
            <div class="tab-pane fade show active" id="pills-transit" role="tabpanel"
                 aria-labelledby="tab-transit">
                <form id="transitForm" action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-center mb-4">
                        <h2 class="mb-2" style="color: #667eea; font-weight: 600;">WEB TRANSIT</h2>
                        <p class="text-muted">Upload your CMR and Invoice documents</p>
                    </div>

                    <div id="transitDocuments" class="transit-documents">
                        <div class="document-row mb-4 p-3 border rounded" data-row="0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 text-primary">Document Set #1</h5>
                                <span class="badge bg-secondary">Required</span>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" for="cmr_0">
                                        <i class="fas fa-file-pdf text-danger"></i> CMR Document
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" name="cmr[]" class="form-control file-input" 
                                               id="cmr_0" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <label for="cmr_0" class="file-upload-label">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <div class="file-name">Choose CMR file...</div>
                                            <small class="text-muted">PDF, JPG, PNG (Max 10MB)</small>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" for="invoice_0">
                                        <i class="fas fa-file-invoice text-success"></i> İNVOYS Document
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" name="invoice[]" class="form-control file-input" 
                                               id="invoice_0" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <label for="invoice_0" class="file-upload-label">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <div class="file-name">Choose Invoice file...</div>
                                            <small class="text-muted">PDF, JPG, PNG (Max 10MB)</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button type="button" class="btn btn-outline-primary" id="addDocumentRow">
                            <i class="fas fa-plus"></i> Add More Documents
                        </button>
                        <span class="text-muted" id="documentCount">1 document set</span>
                    </div>

                    <div class="alert alert-info d-none" id="formAlert">
                        <i class="fas fa-info-circle"></i> <span id="alertMessage"></span>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="1" id="transitCheck" required>
                        <label class="form-check-label" for="transitCheck">
                            I have read and agree to the <a href="#" class="text-primary" data-toggle="modal" data-target="#termsModal">terms and conditions</a>
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg" id="submitBtn">
                            <i class="fas fa-credit-card"></i> Proceed to Payment
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-2">Hər hansısa sualınız var?</p>
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
                        <h2 class="mb-3">Short Import Declaration</h2>
                    </div>
                    <div class="alert alert-success">
                        <i class="fas fa-clock"></i> Coming Soon
                        <p class="mb-0 mt-2">This service will be available shortly. Please check back later.</p>
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
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please read our terms and conditions carefully before using our services.</p>
                <!-- Add terms content here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let rowCount = 1;

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

        label.find('.file-name').text(fileName);
        label.addClass('has-file');
    });

    // Add new document row
    $('#addDocumentRow').on('click', function() {
        const newRow = `
            <div class="document-row mb-4 p-3 border rounded" data-row="${rowCount}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 text-primary">Document Set #${rowCount + 1}</h5>
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold" for="cmr_${rowCount}">
                            <i class="fas fa-file-pdf text-danger"></i> CMR Document
                        </label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="cmr[]" class="form-control file-input" 
                                   id="cmr_${rowCount}" accept=".pdf,.jpg,.jpeg,.png">
                            <label for="cmr_${rowCount}" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <div class="file-name">Choose CMR file...</div>
                                <small class="text-muted">PDF, JPG, PNG (Max 10MB)</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold" for="invoice_${rowCount}">
                            <i class="fas fa-file-invoice text-success"></i> İNVOYS Document
                        </label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="invoice[]" class="form-control file-input" 
                                   id="invoice_${rowCount}" accept=".pdf,.jpg,.jpeg,.png">
                            <label for="invoice_${rowCount}" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <div class="file-name">Choose Invoice file...</div>
                                <small class="text-muted">PDF, JPG, PNG (Max 10MB)</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#transitDocuments').append(newRow);
        rowCount++;
        updateDocumentCount();
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
        $('#documentCount').text(count + ' document set' + (count > 1 ? 's' : ''));
    }

    // Show alert
    function showAlert(message, type = 'info') {
        const alert = $('#formAlert');
        alert.removeClass('d-none alert-info alert-danger alert-success')
             .addClass('alert-' + type);
        $('#alertMessage').text(message);
        setTimeout(() => alert.fadeOut(), 5000);
    }

    // Form validation
    $('#transitForm').on('submit', function(e) {
        if (!$('#transitCheck').is(':checked')) {
            e.preventDefault();
            showAlert('Please accept the terms and conditions to continue.', 'danger');
            $('#transitCheck').focus();
            return false;
        }

        const hasFiles = $('.file-input').filter(function() {
            return $(this).val() !== '';
        }).length > 0;

        if (!hasFiles) {
            e.preventDefault();
            showAlert('Please upload at least one CMR and Invoice document.', 'danger');
            return false;
        }

        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    });
});
</script>

<style>
.document-row {
    background: #f8f9fa;
    transition: all 0.3s ease;
}
.document-row:hover {
    background: #ffffff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
    transition: transform 0.3s ease;
}
.social-links a:hover {
    transform: translateY(-5px);
}
</style>
@endsection
