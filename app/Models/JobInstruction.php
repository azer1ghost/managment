<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Altek\Accountant\Contracts\Recordable;
use App\Interfaces\DocumentableInterface;
use Illuminate\Database\Eloquent\Model;
use Altek\Eventually\Eventually;
use App\Traits\Documentable;
use App\Traits\GetClassInfo;

class JobInstruction extends Model implements DocumentableInterface, Recordable
{
    use SoftDeletes, Documentable, GetClassInfo, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['user_id'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }

    public function getMainColumn(): string
    {
        return $this->getRelationValue('users')->getAttribute('name');
    }
}
