<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AccessRate extends Model
{
    protected $fillable = ['position_id', 'folder_id', 'is_readonly', 'is_change', 'is_print'];

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class,'positions_access_rates_relationship');
    }
    public function folders(): BelongsTo
    {
        return $this->belongsTo(Folder::class,'folder_id')->withDefault();
    }
}
