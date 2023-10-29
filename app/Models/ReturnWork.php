<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReturnWork extends Model
{
    protected $table = 'returns_works';
    protected $fillable = ['work_id', 'return_reason', 'main_reason', 'name', 'phone', 'note'];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class)->withDefault();
    }
}
