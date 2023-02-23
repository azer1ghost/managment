<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessRate extends Model
{
    protected $fillable = ['position_id', 'composition', 'folder_id', 'is_readonly', 'is_change', 'is_print'];

    public function positions(): BelongsTo
    {
        return $this->belongsTo(Position::class,'position_id')->withDefault();
    }
    public function folders(): BelongsTo
    {
        return $this->belongsTo(Folder::class,'folder_id')->withDefault();
    }
}
