<style>
    .editable-parameter {
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .editable-parameter:hover {
        background-color: #f0f8ff !important;
    }
    .editable-parameter .parameter-value {
        display: inline-block;
        min-width: 80px;
    }
    .editable-parameter .editing-input {
        width: 100px !important;
        display: inline-block;
        margin: 0;
    }
    .save-indicator {
        display: inline-block;
        margin-left: 5px;
        vertical-align: middle;
    }
    .editable-date {
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .editable-date:hover:not([data-disabled="true"]) {
        background-color: #f0f8ff !important;
    }
    .editable-date[data-disabled="true"] {
        cursor: not-allowed;
        opacity: 0.6;
    }
    .editable-date .date-value {
        display: inline-block;
        min-width: 100px;
    }
    .editable-date .editing-date-input {
        width: 150px !important;
        display: inline-block;
        margin: 0;
    }
    .payment-panel {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .payment-panel .form-group {
        margin-bottom: 10px;
    }
    .payment-panel label {
        font-weight: 600;
        margin-bottom: 5px;
    }
    .partial-fields {
        display: none;
    }
    .partial-fields.show {
        display: block;
    }
    .invoice-summary-panel {
        background-color: #e8f4f8;
        border: 1px solid #bee5eb;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
    }
    .invoice-summary-panel h5 {
        color: #0c5460;
        font-weight: 600;
    }
    .invoice-summary-panel strong {
        color: #0c5460;
    }
</style>

@php
    $invoiceCode = $works->first()->code ?? null;
    $today = now()->format('Y-m-d');
    
    // Calculate invoice totals
    $totalMain = 0;
    $totalVat = 0;
    $totalPaidMain = 0;
    $totalPaidVat = 0;
    
    foreach ($works as $work) {
        $totalMain += $work->getParameterValue(\App\Models\Work::AMOUNT) ?? 0;
        $totalVat += $work->getParameterValue(\App\Models\Work::VAT) ?? 0;
        $totalPaidMain += $work->getParameterValue(\App\Models\Work::PAID) ?? 0;
        $totalPaidVat += $work->getParameterValue(\App\Models\Work::VATPAYMENT) ?? 0;
    }
    
    $remainingMain = $totalMain - $totalPaidMain;
    $remainingVat = $totalVat - $totalPaidVat;
@endphp

@if($invoiceCode)
<!-- Invoice Total Summary -->
<div class="invoice-summary-panel mb-3">
    <h5 class="mb-2">Invoice Total Summary</h5>
    <div class="row">
        <div class="col-md-3">
            <strong>Total Main:</strong> <span id="summaryTotalMain">{{ number_format($totalMain, 2) }}</span> AZN
        </div>
        <div class="col-md-3">
            <strong>Total VAT:</strong> <span id="summaryTotalVat">{{ number_format($totalVat, 2) }}</span> AZN
        </div>
        <div class="col-md-3">
            <strong>Paid Main:</strong> <span id="summaryPaidMain">{{ number_format($totalPaidMain, 2) }}</span> AZN
        </div>
        <div class="col-md-3">
            <strong>Paid VAT:</strong> <span id="summaryPaidVat">{{ number_format($totalPaidVat, 2) }}</span> AZN
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-3">
            <strong>Remaining Main:</strong> <span id="summaryRemainingMain" class="text-danger">{{ number_format($remainingMain, 2) }}</span> AZN
        </div>
        <div class="col-md-3">
            <strong>Remaining VAT:</strong> <span id="summaryRemainingVat" class="text-danger">{{ number_format($remainingVat, 2) }}</span> AZN
        </div>
    </div>
</div>

<!-- Unified Payment Panel -->
<div class="payment-panel">
    <h5 class="mb-3">Unified Payment Panel</h5>
    <form id="unifiedPaymentForm">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="paymentType">Payment Type</label>
                    <select id="paymentType" name="paymentType" class="form-control">
                        <option value="full">Full Payment</option>
                        <option value="partial">Partial Payment</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 partial-fields">
                <div class="form-group">
                    <label for="partialMain">Paid Main Amount</label>
                    <input type="number" 
                           id="partialMain" 
                           name="partialMain" 
                           step="0.01" 
                           min="0"
                           placeholder="Paid Main Amount" 
                           class="form-control">
                </div>
            </div>
            <div class="col-md-3 partial-fields">
                <div class="form-group">
                    <label for="partialVat">Paid VAT Amount</label>
                    <input type="number" 
                           id="partialVat" 
                           name="partialVat" 
                           step="0.01" 
                           min="0"
                           placeholder="Paid VAT Amount" 
                           class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="globalPaymentDate">Global Payment Date</label>
                    <div class="input-group">
                        <input type="date" 
                               id="globalPaymentDate" 
                               name="globalPaymentDate" 
                               value="{{ $today }}"
                               class="form-control">
                        <div class="input-group-append">
                            <button type="button" id="clearPaymentDate" class="btn btn-outline-secondary" title="Clear Date">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="button" id="clearAllPayments" class="btn btn-warning btn-lg mr-2">
                    <i class="fa fa-eraser"></i> Clear All Paid Amounts
                </button>
                <button type="button" id="applyPayment" class="btn btn-primary btn-lg">
                    <i class="fa fa-check"></i> Apply Payment to All Tasks
                </button>
            </div>
        </div>
    </form>
</div>
@endif

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
        <tr class="text-center">
            <th>İş ID</th>
            <th>İş adı</th>
            <th>Qaimə nömrəsi</th>
            <th>Əsas məbləğ (AMOUNT)</th>
            <th>Əsas məbləğdən ödənilən (PAID)</th>
            <th>ƏDV məbləği (VAT)</th>
            <th>ƏDV-dən ödənilən (VATPAYMENT)</th>
            <th>Qeyri-rəsmi məbləğ (ILLEGALAMOUNT)</th>
            <th>Qeyri-rəsmi ödənilən (ILLEGALPAID)</th>
            <th>Əsas ödəniş tarixi</th>
            <th>ƏDV ödəniş tarixi</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($works as $work)
            @php
                $amount = $work->getParameterValue(\App\Models\Work::AMOUNT) ?? 0;
                $paid = $work->getParameterValue(\App\Models\Work::PAID) ?? 0;
                $vat = $work->getParameterValue(\App\Models\Work::VAT) ?? 0;
                $vatPayment = $work->getParameterValue(\App\Models\Work::VATPAYMENT) ?? 0;
                $illegalAmount = $work->getParameterValue(\App\Models\Work::ILLEGALAMOUNT) ?? 0;
                $illegalPaid = $work->getParameterValue(\App\Models\Work::ILLEGALPAID) ?? 0;
                $isFullyPaid = $work->isFullyPaid();
            @endphp
            <tr>
                <td>{{ $work->id }}</td>
                <td>{{ $work->getRelationValue('service')->getAttribute('name') ?? '-' }}</td>
                <td>{{ $work->code ?? '-' }}</td>
                <td class="editable-parameter" 
                    data-work-id="{{ $work->id }}" 
                    data-parameter-id="{{ \App\Models\Work::AMOUNT }}"
                    data-amount="{{ $amount }}"
                    style="cursor: pointer; position: relative;">
                    <span class="parameter-value">{{ number_format($amount, 2) }}</span>
                    <span class="save-indicator" style="display: none; margin-left: 5px;"></span>
                </td>
                <td class="editable-parameter" 
                    data-work-id="{{ $work->id }}" 
                    data-parameter-id="{{ \App\Models\Work::PAID }}"
                    data-paid="{{ $paid }}"
                    style="cursor: pointer; position: relative;">
                    <span class="parameter-value">{{ number_format($paid, 2) }}</span>
                    <span class="save-indicator" style="display: none; margin-left: 5px;"></span>
                </td>
                <td class="editable-parameter" 
                    data-work-id="{{ $work->id }}" 
                    data-parameter-id="{{ \App\Models\Work::VAT }}"
                    data-vat="{{ $vat }}"
                    style="cursor: pointer; position: relative;">
                    <span class="parameter-value">{{ number_format($vat, 2) }}</span>
                    <span class="save-indicator" style="display: none; margin-left: 5px;"></span>
                </td>
                <td class="editable-parameter" 
                    data-work-id="{{ $work->id }}" 
                    data-parameter-id="{{ \App\Models\Work::VATPAYMENT }}"
                    data-vat-payment="{{ $vatPayment }}"
                    style="cursor: pointer; position: relative;">
                    <span class="parameter-value">{{ number_format($vatPayment, 2) }}</span>
                    <span class="save-indicator" style="display: none; margin-left: 5px;"></span>
                </td>
                <td class="editable-parameter" 
                    data-work-id="{{ $work->id }}" 
                    data-parameter-id="{{ \App\Models\Work::ILLEGALAMOUNT }}"
                    data-illegal-amount="{{ $illegalAmount }}"
                    style="cursor: pointer; position: relative;">
                    <span class="parameter-value">{{ number_format($illegalAmount, 2) }}</span>
                    <span class="save-indicator" style="display: none; margin-left: 5px;"></span>
                </td>
                <td class="editable-parameter" 
                    data-work-id="{{ $work->id }}" 
                    data-parameter-id="{{ \App\Models\Work::ILLEGALPAID }}"
                    data-illegal-paid="{{ $illegalPaid }}"
                    style="cursor: pointer; position: relative;">
                    <span class="parameter-value">{{ number_format($illegalPaid, 2) }}</span>
                    <span class="save-indicator" style="display: none; margin-left: 5px;"></span>
                </td>
                <td class="editable-date" 
                    data-work-id="{{ $work->id }}" 
                    data-field="paid_at"
                    style="cursor: pointer; position: relative;"
                    @if($isFullyPaid) data-disabled="true" @endif>
                    <span class="date-value">{{ $work->paid_at ? $work->paid_at->format('Y-m-d') : '-' }}</span>
                    <span class="save-indicator" style="display: none; margin-left: 5px;"></span>
                </td>
                <td class="editable-date" 
                    data-work-id="{{ $work->id }}" 
                    data-field="vat_date"
                    style="cursor: pointer; position: relative;"
                    @if($isFullyPaid) data-disabled="true" @endif>
                    <span class="date-value">{{ $work->vat_date ? $work->vat_date->format('Y-m-d') : '-' }}</span>
                    <span class="save-indicator" style="display: none; margin-left: 5px;"></span>
                </td>
                <td>
                    @if($isFullyPaid)
                        <span class="badge badge-success">Tam ödənilib</span>
                    @else
                        <button class="btn btn-success btn-sm completePayment" data-id="{{ $work->id }}">
                            Tam ödəniş et
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
(function() {
    // Use IIFE to avoid conflicts
    'use strict';
    
    // Initialize when document is ready or when modal content is loaded
    function initInlineEditing() {
        // Remove any existing handlers to prevent duplicates
        $(document).off('click', '.editable-parameter');
        
        // Handle inline editing for payment parameters
        $(document).on('click', '.editable-parameter', function(e) {
            e.stopPropagation();
            
            const $cell = $(this);
            const $valueSpan = $cell.find('.parameter-value');
            
            // Don't edit if already in edit mode
            if ($cell.find('input.editing-input').length > 0) {
                return;
            }
            
            // Get current value - remove formatting
            const currentText = $valueSpan.text().trim();
            const currentValue = parseFloat(currentText.replace(/[^\d.-]/g, '')) || 0;
            const workId = $cell.data('work-id');
            const parameterId = $cell.data('parameter-id');
            
            if (!workId || !parameterId) {
                console.error('Missing work_id or parameter_id');
                return;
            }
            
            // Create input field
            const $input = $('<input>', {
                type: 'number',
                step: '0.01',
                class: 'form-control form-control-sm editing-input',
                value: currentValue,
                style: 'width: 100px; display: inline-block;'
            });
            
            // Store original value for cancel
            const originalValue = currentValue;
            
            // Replace span with input
            $valueSpan.hide();
            $input.insertAfter($valueSpan);
            $input.focus().select();
            
            // Save on blur
            $input.on('blur', function() {
                const newValue = parseFloat($(this).val()) || 0;
                // Only save if value changed
                if (newValue !== originalValue) {
                    saveParameter($cell, $input, $valueSpan, workId, parameterId, newValue);
                } else {
                    // Just restore display
                    $input.remove();
                    $valueSpan.show();
                }
            });
            
            // Save on Enter key, cancel on Escape
            $input.on('keydown', function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    e.preventDefault();
                    $(this).blur();
                }
                // Cancel editing if Escape is pressed
                if (e.key === 'Escape' || e.keyCode === 27) {
                    e.preventDefault();
                    $input.remove();
                    $valueSpan.show();
                }
            });
        });
    }
    
    function saveParameter($cell, $input, $valueSpan, workId, parameterId, newValue) {
        const $indicator = $cell.find('.save-indicator');
        
        // Remove input and show loading
        $input.remove();
        $valueSpan.text('...').show();
        $indicator.html('<i class="fa fa-spinner fa-spin text-info" style="font-size: 12px;"></i>').show();
        
        $.ajax({
            url: '{{ route("works.update-invoice-parameter") }}',
            method: 'POST',
            dataType: 'json',
            data: {
                work_id: workId,
                parameter_id: parameterId,
                value: newValue,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    // Update displayed value with formatted number
                    const formattedValue = res.value || parseFloat(newValue).toFixed(2);
                    $valueSpan.text(formattedValue);
                    
                    // Show green success indicator
                    $indicator.html('<i class="fa fa-check text-success" style="font-size: 12px;" title="Yadda saxlanıldı"></i>').show();
                    
                    // Hide indicator after 2 seconds
                    setTimeout(function() {
                        $indicator.fadeOut(300, function() {
                            $(this).hide();
                        });
                    }, 2000);
                } else {
                    showError($valueSpan, $indicator, res.error || 'Yadda saxlanılmadı');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Xəta baş verdi';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showError($valueSpan, $indicator, errorMsg);
            }
        });
    }
    
    function showError($valueSpan, $indicator, errorMsg) {
        // Restore original value on error (or keep current)
        // Show red error indicator
        $indicator.html('<i class="fa fa-times text-danger" style="font-size: 12px;" title="' + errorMsg + '"></i>').show();
        
        // Hide indicator after 3 seconds
        setTimeout(function() {
            $indicator.fadeOut(300, function() {
                $(this).hide();
            });
        }, 3000);
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        $(document).ready(function() {
            initInlineEditing();
            initDateEditing();
            initUnifiedPayment();
        });
    } else {
        // DOM already loaded (content loaded dynamically)
        initInlineEditing();
        initDateEditing();
        initUnifiedPayment();
    }
    
    // Unified Payment Panel
    function initUnifiedPayment() {
        // Check if payment panel exists
        if ($('#paymentType').length === 0) {
            return; // Payment panel not available
        }
        
        const invoiceCode = '{{ $invoiceCode ?? "" }}';
        
        if (!invoiceCode) {
            $('#applyPayment').prop('disabled', true).attr('title', 'Invoice code not available');
            return;
        }
        
        // Show/hide partial fields based on payment type
        $('#paymentType').on('change', function() {
            if ($(this).val() === 'partial') {
                $('.partial-fields').addClass('show');
            } else {
                $('.partial-fields').removeClass('show');
                $('#partialMain').val('');
                $('#partialVat').val('');
            }
        });
        
        // Handle Apply Payment button
        $('#applyPayment').on('click', function() {
            const paymentType = $('#paymentType').val();
            const paymentDate = $('#paymentDate').val();
            const partialMain = parseFloat($('#partialMain').val()) || 0;
            const partialVat = parseFloat($('#partialVat').val()) || 0;
            
            // Validation
            if (!paymentDate) {
                alert('Please select a payment date.');
                return;
            }
            
            if (paymentType === 'partial') {
                if (partialMain <= 0 && partialVat <= 0) {
                    alert('Please enter at least one partial payment amount.');
                    return;
                }
            }
            
            if (!invoiceCode) {
                alert('Invoice code not found.');
                return;
            }
            
            // Disable button and show loading
            const $button = $(this);
            const originalText = $button.html();
            $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            
            // Prepare data
            const data = {
                invoice: invoiceCode,
                paymentType: paymentType,
                paymentDate: paymentDate,
                partialMain: partialMain,
                partialVat: partialVat,
                _token: '{{ csrf_token() }}'
            };
            
            $.ajax({
                url: '{{ route("works.apply-unified-payment") }}',
                method: 'POST',
                dataType: 'json',
                data: data,
                success: function(res) {
                    if (res.success) {
                        // Show success message
                        alert('Payment applied successfully to ' + res.affected_works + ' task(s)!');
                        
                        // Refresh modal content by re-fetching
                        $.ajax({
                            url: '{{ route("works.invoice.fetch") }}',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                invoice_code: '{{ $invoiceCode }}',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(fetchRes) {
                                if (fetchRes && fetchRes.html) {
                                    $('#invoiceModalBody').html(fetchRes.html);
                                } else {
                                    // Fallback: close modal and reload page
                                    $('#invoiceModal').modal('hide');
                                    setTimeout(function() {
                                        location.reload();
                                    }, 500);
                                }
                            },
                            error: function() {
                                // Fallback: close modal and reload page
                                $('#invoiceModal').modal('hide');
                                setTimeout(function() {
                                    location.reload();
                                }, 500);
                            }
                        });
                    } else {
                        alert('Error: ' + (res.error || 'Payment application failed'));
                        $button.prop('disabled', false).html(originalText);
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Xəta baş verdi';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert('Error: ' + errorMsg);
                    $button.prop('disabled', false).html(originalText);
                }
            });
        });
    }
    
    // Date field inline editing
    function initDateEditing() {
        // Remove any existing handlers to prevent duplicates
        $(document).off('click', '.editable-date');
        
        // Handle inline editing for date fields
        $(document).on('click', '.editable-date', function(e) {
            e.stopPropagation();
            
            const $cell = $(this);
            
            // Don't edit if disabled
            if ($cell.data('disabled') === true) {
                return;
            }
            
            const $valueSpan = $cell.find('.date-value');
            
            // Don't edit if already in edit mode
            if ($cell.find('input.editing-date-input').length > 0) {
                return;
            }
            
            const currentValue = $valueSpan.text().trim();
            const workId = $cell.data('work-id');
            const fieldName = $cell.data('field');
            
            if (!workId || !fieldName) {
                console.error('Missing work_id or field name');
                return;
            }
            
            // Create date input field
            const $input = $('<input>', {
                type: 'date',
                class: 'form-control form-control-sm editing-date-input',
                value: currentValue !== '-' ? currentValue : '',
                style: 'width: 150px; display: inline-block;'
            });
            
            // Store original value for cancel
            const originalValue = currentValue;
            
            // Replace span with input
            $valueSpan.hide();
            $input.insertAfter($valueSpan);
            $input.focus();
            
            // Save on change (date selection)
            $input.on('change', function() {
                const newValue = $(this).val();
                if (newValue && newValue !== originalValue) {
                    saveDate($cell, $input, $valueSpan, workId, fieldName, newValue);
                } else if (!newValue && originalValue !== '-') {
                    // Clear date
                    saveDate($cell, $input, $valueSpan, workId, fieldName, '');
                } else {
                    // No change, just restore
                    $input.remove();
                    $valueSpan.show();
                }
            });
            
            // Cancel on Escape
            $input.on('keydown', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    e.preventDefault();
                    $input.remove();
                    $valueSpan.show();
                }
            });
        });
    }
    
    function saveDate($cell, $input, $valueSpan, workId, fieldName, newValue) {
        const $indicator = $cell.find('.save-indicator');
        
        // Remove input and show loading
        $input.remove();
        $valueSpan.text('...').show();
        $indicator.html('<i class="fa fa-spinner fa-spin text-info" style="font-size: 12px;"></i>').show();
        
        $.ajax({
            url: '{{ route("works.update-invoice-date") }}',
            method: 'POST',
            dataType: 'json',
            data: {
                work_id: workId,
                field: fieldName,
                value: newValue,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    // Update displayed value
                    const displayValue = res.value || newValue || '-';
                    $valueSpan.text(displayValue);
                    
                    // Show green success indicator
                    $indicator.html('<i class="fa fa-check text-success" style="font-size: 12px;" title="Yadda saxlanıldı"></i>').show();
                    
                    // Hide indicator after 2 seconds
                    setTimeout(function() {
                        $indicator.fadeOut(300, function() {
                            $(this).hide();
                        });
                    }, 2000);
                } else {
                    showDateError($valueSpan, $indicator, res.error || 'Yadda saxlanılmadı');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Xəta baş verdi';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showDateError($valueSpan, $indicator, errorMsg);
            }
        });
    }
    
    function showDateError($valueSpan, $indicator, errorMsg) {
        // Show red error indicator
        $indicator.html('<i class="fa fa-times text-danger" style="font-size: 12px;" title="' + errorMsg + '"></i>').show();
        
        // Hide indicator after 3 seconds
        setTimeout(function() {
            $indicator.fadeOut(300, function() {
                $(this).hide();
            });
        }, 3000);
    }
})();
</script>

