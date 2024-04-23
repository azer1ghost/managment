<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use App\Traits\GetClassInfo;
use App\Traits\Resultable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalRelation extends Model implements DocumentableInterface, Recordable
{
    use  Documentable, Resultable, GetClassInfo, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['user_id', 'department_id', 'case', 'applicant', 'reciever', 'tool', 'contact_time', 'ordering', 'is_foreign'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }

    public function departments(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id')->withDefault();
    }
    public function getMainColumn(): string
    {
        return $this->getAttribute('id');
    }
}
