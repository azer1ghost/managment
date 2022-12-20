<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Altek\Accountant\Contracts\Recordable;
use Illuminate\Database\Eloquent\Model;
use Altek\Eventually\Eventually;

class CustomerEngagement extends Model implements Recordable
{
    use SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['user_id', 'client_id', 'partner_id', 'executant'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class)->withDefault();
    }

    public function executants(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executant')->withDefault();
    }
}
