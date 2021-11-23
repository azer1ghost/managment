<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkStatusLog extends Model
{
    use HasFactory;

    protected $fillable = ['work_id', 'status', 'updated_at'];

    protected $casts = ['updated_at' => 'datetime'];

    public $timestamps = false;

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class)->withDefault();
    }
}