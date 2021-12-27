<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model implements Recordable
{
    use SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['chief_id'];

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
}
