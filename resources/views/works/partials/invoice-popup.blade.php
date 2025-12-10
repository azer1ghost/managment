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
</style>

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
                <td>
                    <input type="date" 
                           name="paid_at[{{ $work->id }}]" 
                           class="form-control form-control-sm" 
                           value="{{ $work->paid_at ? $work->paid_at->format('Y-m-d') : '' }}"
                           @if($isFullyPaid) disabled @endif>
                </td>
                <td>
                    <input type="date" 
                           name="vat_date[{{ $work->id }}]" 
                           class="form-control form-control-sm" 
                           value="{{ $work->vat_date ? $work->vat_date->format('Y-m-d') : '' }}"
                           @if($isFullyPaid) disabled @endif>
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
        $(document).ready(initInlineEditing);
    } else {
        // DOM already loaded (content loaded dynamically)
        initInlineEditing();
    }
})();
</script>

