<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static insert($array)
 */
class Social extends Model implements Recordable
{
    use SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['name', 'url', 'status', 'company_id'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }
}
