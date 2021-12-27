<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model implements Recordable
{
    use HasFactory, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['name', 'file', 'size', 'type', 'user_id'];

    public function getRouteKeyName()
    {
        return 'file';
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public static function supportedTypeIcons(): array
    {
        return [
            // image
            'image/jpeg' => ['icon' => 'image', 'color' => 'dark'],
            'image/jpg'  => ['icon' => 'image', 'color' => 'dark'],
            'image/png'  => ['icon' => 'image', 'color' => 'dark'],
            // pdf
            'application/pdf' => ['icon' => 'pdf', 'color' => 'danger'],
            // word
            'application/doc' => ['icon' => 'word', 'color' => 'primary'],
            'application/ms-doc' => ['icon' => 'word', 'color' => 'primary'],
            'application/msword' => ['icon' => 'word', 'color' => 'primary'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['icon' => 'word', 'color' => 'primary'],
            // excel
            'application/vnd.ms-excel' => ['icon' => 'excel', 'color' => 'success'],
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['icon' => 'excel', 'color' => 'success']
        ];
    }

    public function module(): string
    {
        $documentableType = $this->getAttribute('documentable_type');
        $modelPos = strpos($documentableType, '\\', strpos($documentableType,  '\\') + strlen('\\')) + 1;
        return substr($documentableType, $modelPos);
    }
}