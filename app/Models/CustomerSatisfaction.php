<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class CustomerSatisfaction extends Model
{
    protected $fillable = ['satisfaction_id'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class)->withDefault();
    }


}
