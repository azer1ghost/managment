<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = ['company', 'client', 'invoiceNo', 'invoiceDate', 'paymentType', 'protocolDate', 'contractNo', 'contractDate', 'services', 'invoiceNumbers', 'is_signed', 'created_by', 'total_amount'];

    protected $dates = ['deleted_at'];

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Prevent updates to saved invoices (immutability)
        static::updating(function ($invoice) {
            // Only allow updating is_signed flag (for signing invoices)
            $allowedFields = ['is_signed', 'updated_at'];
            $dirtyFields = array_keys($invoice->getDirty());
            
            foreach ($dirtyFields as $field) {
                if (!in_array($field, $allowedFields)) {
                    throw new \Exception('Invoices are immutable after creation. Only the signature status can be changed. To modify an invoice, create a duplicate.');
                }
            }
        });

        // Calculate and store total_amount on creation
        static::creating(function ($invoice) {
            if (empty($invoice->total_amount)) {
                $invoice->total_amount = $invoice->calculateTotalAmount();
            }
        });
    }

    public function financeClients(): BelongsTo
    {
        return $this->belongsTo(FinanceClient::class, 'client')->withDefault();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * Get total invoice amount (uses stored value, not recalculated)
     * 
     * @return float
     */
    public function getTotalAmountAttribute(): float
    {
        // Use stored value if available, otherwise calculate (for backward compatibility)
        if (isset($this->attributes['total_amount']) && $this->attributes['total_amount'] !== null) {
            return (float) $this->attributes['total_amount'];
        }

        // Fallback: calculate if not stored (for existing invoices)
        return $this->calculateTotalAmount();
    }

    /**
     * Calculate total invoice amount from services JSON
     * Formula: Sum of (count * unit_price) for all services, then apply VAT multiplier
     * This method is used only during creation to store the value
     * 
     * @return float
     */
    public function calculateTotalAmount(): float
    {
        $services = json_decode($this->services, true);
        if (!is_array($services) || empty($services)) {
            return 0;
        }

        // Calculate sum of all services: count * unit_price for each service
        $sum = 0;
        foreach ($services as $service) {
            $count = isset($service['input3']) && is_numeric($service['input3']) ? (float) $service['input3'] : 0;
            $unitPrice = isset($service['input4']) && is_numeric($service['input4']) ? (float) $service['input4'] : 0;
            $sum += $count * $unitPrice;
        }

        // Apply VAT multiplier based on company type
        // Companies with VAT: mbrokerRespublika, mtechnologiesRespublika, garantRespublika, 
        // garantKapital, mbrokerKapital, mtechnologiesKapital use 1.18 (18% VAT included)
        // Other companies use 1.0 (no VAT)
        $company = $this->company ?? '';
        $vatMultiplier = in_array($company, [
            'mbrokerRespublika',
            'mtechnologiesRespublika',
            'garantRespublika',
            'garantKapital',
            'mbrokerKapital',
            'mtechnologiesKapital'
        ]) ? 1.18 : 1.0;

        // Final total = sum * VAT multiplier
        return $sum * $vatMultiplier;
    }
}
