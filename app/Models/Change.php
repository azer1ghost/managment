<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Change extends Model implements DocumentableInterface, Recordable

{
    use Documentable, Eventually, \Altek\Accountant\Recordable, GetClassInfo;

    protected $fillable = ['user_id', 'department_id', 'description', 'reason', 'result', 'responsible', 'effectivity', 'note', 'datetime'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }

    public function departments(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id')->withDefault();
    }
    public function responsibles(): BelongsTo
    {
        return $this->belongsTo(User::class,'responsible')->withDefault();
    }
    public function getMainColumn(): string
    {
        return $this->getAttribute('id');
    }
}
