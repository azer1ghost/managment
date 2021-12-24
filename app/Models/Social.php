<?php

namespace App\Models;

use App\Traits\Loger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static insert($array)
 */
class Social extends Model
{
    use Loger, SoftDeletes;

    protected $fillable = ['name', 'url', 'status', 'company_id'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }
}
