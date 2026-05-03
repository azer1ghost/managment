<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'status',
        'note',
        'is_absent',
        'approved',
    ];

    protected $casts = [
        'date' => 'date',
        'is_absent' => 'boolean',
        'approved' => 'boolean',
    ];

    public static array $statuses = [
        'B'   => ['label' => 'Bayram',                   'color' => '#cce5ff', 'text' => '#004085'],
        'İ'   => ['label' => 'İstirahət',                'color' => '#e2e3e5', 'text' => '#383d41'],
        'E'   => ['label' => 'Ezamiyyət',                'color' => '#fff3cd', 'text' => '#856404'],
        'Ə.M' => ['label' => 'Əmək Məzuniyyəti',         'color' => '#d4edda', 'text' => '#155724'],
        'X'   => ['label' => 'Xəstəlik',                 'color' => '#f8d7da', 'text' => '#721c24'],
        'A.M' => ['label' => 'Analıq/Atalıq Məzuniyyəti','color' => '#e2d9f3', 'text' => '#432874'],
        'Ö'   => ['label' => 'Ödənişsiz Məzuniyyət',     'color' => '#fefefe', 'text' => '#6c757d'],
        'ÜS'  => ['label' => 'Əm.haq. saxlanılma',       'color' => '#fde8d8', 'text' => '#7d3c0b'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
