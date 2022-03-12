<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo, SoftDeletes};
use Altek\{Accountant\Contracts\Recordable, Eventually\Eventually};

class Meeting extends Model implements Recordable
{
    use HasFactory, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['name', 'department_id', 'will_start_at', 'will_end_at'];

    protected $casts = ['will_start_at' => 'datetime', 'will_end_at' => 'datetime'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
    }
}
