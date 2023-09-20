<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Creditor extends Model
{
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
}
