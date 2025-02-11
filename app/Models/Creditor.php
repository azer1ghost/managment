<?php

namespace App\Models;

use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Creditor extends Model implements DocumentableInterface

{
    use Documentable, Eventually;

    protected $fillable = [
        'supplier_id',
        'company_id',
        'creditor',
        'amount',
        'vat',
        'paid',
        'vat_paid',
        'status',
        'note',
        'painted',
        'paid_at',
        'last_date',
        'overhead_at',
        'overhead',
        'doc',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public static function statuses(): array
    {
        return [1 => 1, 2, 3];
    }

    public function getMainColumn(): string
    {
        return $this->getRelationValue('creditor')->getAttribute('name');
    }

    public function getSupplierName()
    {
        if (is_null($this->getAttribute('supplier_id'))) {
            return $this->getAttribute('creditor');
        } else {
            return $this->getRelationValue('supplier')->getAttribute('name');
        }
    }
}
