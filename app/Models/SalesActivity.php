<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesActivity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'datetime',
        'address',
        'activity_area',
        'client_id',
        'result',
        'organization_id',
        'certificate_id',
        'sales_activity_type_id',
        'user_id'
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class)->withDefault();
    }

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class)->withDefault();
    }

    public function salesActivityType(): BelongsTo
    {
        return $this->belongsTo(SalesActivityType::class, 'sales_activity_type_id')->withDefault();
    }

    public function salesSupplies(): HasMany
    {
        return $this->hasMany(SalesActivitiesSupply::class, 'sales_activity_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(SalesClient::class, 'client_id')->withDefault();
    }
    public function scopeMonthly($query)
    {
        return $query->where('datetime', '>=', now()->firstOfMonth());
    }
}
