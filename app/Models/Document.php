<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'file', 'size', 'type', 'user_id'];

    public function documentable(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function module(): string
    {
        $documentableType = $this->getAttribute('documentable_type');
        $modelPos = strpos($documentableType, '\\', strpos($documentableType,  '\\') + strlen('\\')) + 1;
        return substr($documentableType, $modelPos);
    }
}