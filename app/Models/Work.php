<?php

namespace App\Models;

use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    SoftDeletes};

class Work extends Model implements DocumentableInterface
{
    use HasFactory, SoftDeletes, Documentable;

    const PENDING = 1;
    const STARTED = 2;
    const DONE = 3;
    const REJECTED = 4;

    protected $fillable = [
        'earning',
        'currency',
        'currency_rate',
        'detail',
        'creator_id',
        'user_id',
        'department_id',
        'asan_imza_id',
        'service_id',
        'client_id',
        'hard_level',
        'status',
        'datetime',
        'verified_at',
        'price_verified_at',
    ];

    protected $casts = ['done_at' => 'datetime'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class)->withDefault();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'work_parameter')->withPivot('value');
    }

    public static function hardLevels(): array
    {
        return [1 => 1, 2, 3];
    }

    public static function statuses(): array
    {
        return [1 => 1, 2, 3, 4];
    }

    public static function userCanViewAll(): bool
    {
        $user = auth()->user();
        return $user->isDeveloper() || $user->isAdministrator() || $user->hasPermission('viewAll-work');
    }

    public static function userCannotViewAll(): bool
    {
        return !self::userCanViewAll();
    }

    public static function userCanViewDepartmentWorks(): bool
    {
        $user = auth()->user();
        return $user->isDeveloper() || $user->isAdministrator() || $user->hasPermission('viewAllDepartment-work');
    }

    public static function userCannotViewDepartmentWorks(): bool
    {
        return !self::userCanViewDepartmentWorks();
    }

    public static function generateCustomCode($prefix = 'MGW', $digits = 8): string
    {
        do {
            $code = $prefix . str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            if (! self::select('code')->withTrashed()->whereCode($code)->exists()) {
                break;
            }
        } while (true);

        return $code;
    }
}
