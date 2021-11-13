<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    const LEGAL = 0;
    const PHYSICAL  = 1;

    protected $fillable = [
        'fullname',
        'father',
        'gender',
        'serial_pattern',
        'serial',
        'fin',
        'phone2',
        'phone1',
        'email2',
        'email1',
        'address1',
        'address2',
        'voen',
        'position',
        'type',
        'client_id'
    ];

    protected $casts = ['type' => 'boolean'];

    public function clients(): HasMany
    {
        return $this->hasMany(__CLASS__, 'client_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'client_id')->withDefault();
    }
}