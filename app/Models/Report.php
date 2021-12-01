<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $fillable = ['chief_id'];

    public function chief(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chief_id')->withDefault();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(DailyReport::class);
    }

    public static function canViewAll()
    {
        $user = auth()->user();
        return $user->isDeveloper() || $user->isAdministrator() || $user->hasPermission('viewAll-report');
    }

    public static function cannotViewAll()
    {
        return !self::canViewAll();
    }
}
