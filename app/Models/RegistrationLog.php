<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationLog extends Model implements Recordable, DocumentableInterface

{
    use Documentable, \Altek\Accountant\Recordable,  Eventually;

    protected $fillable = ['performer', 'receiver', 'sender', 'number', 'description', 'arrived_at', 'received_at'];

    public function performers(): BelongsTo
    {
        return $this->belongsTo(User::class,'performer')->withDefault();
    }
    public function receivers(): BelongsTo
    {
        return $this->belongsTo(User::class,'receiver')->withDefault();
    }
    public function getMainColumn(): string
    {
        return $this->getAttribute('id');
    }
}
