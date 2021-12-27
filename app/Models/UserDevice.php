<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDevice extends Model implements Recordable
{
    use \Altek\Accountant\Recordable, Eventually;

    protected $primaryKey = 'device_key';
    protected $fillable = ['user_id', 'device', 'device_key', 'fcm_token', 'ip', 'location'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}