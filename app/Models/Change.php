<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Change extends Model implements Recordable, DocumentableInterface

{
    use Documentable, \Altek\Accountant\Recordable,  Eventually;

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

    public static function effectivity(): array
    {
        return [1, 2];
    }
}
