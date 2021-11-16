<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    SoftDeletes};
use Illuminate\Support\Collection;

class Work extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'earning',
        'currency',
        'currency_rate',
        'detail',
        'creator_id',
        'user_id',
        'department_id',
        'service_id',
        'client_id',
        'hard_level'
    ];

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
        return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    }
}
