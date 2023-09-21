<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = ['company', 'client', 'invoiceNo', 'invoiceDate', 'paymentType', 'protocolDate', 'contractNo', 'contractDate', 'services', 'invoiceNumbers', 'is_signed'];

    public function financeClients(): BelongsTo
    {
        return $this->belongsTo(FinanceClient::class, 'client')->withDefault();
    }
}
