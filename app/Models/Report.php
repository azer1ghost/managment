<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Comment\Doc;

class Report extends Model implements Recordable, DocumentableInterface
{
    use SoftDeletes, \Altek\Accountant\Recordable, Eventually, Documentable;

    protected $fillable = ['chief_id', 'document_type'];

    public function chief(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chief_id')->withDefault();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(DailyReport::class)->latest('date');
    }

    public static function canViewAll()
    {
        $user = auth()->user();
        return $user->isDeveloper() || $user->isDirector() || $user->hasPermission('viewAll-report');
    }

    public static function cannotViewAll()
    {
        return !self::canViewAll();
    }
    public function getMainColumn(): string
    {
        return $this->getAttribute('id');
    }
}
