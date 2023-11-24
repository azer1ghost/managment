<?php

namespace App\Models;

use Altek\Eventually\Eventually;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryReport extends Model
{
    use Documentable, Eventually;

    protected $fillable = [
        'user_id',
        'company_id',
        'working_days',
        'actual_days',
        'salary',
        'prize',
        'vacation',
        'advance',
        'date',
        'note',
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

}
