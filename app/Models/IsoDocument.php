<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IsoDocument extends Model implements Recordable, DocumentableInterface

{
    use Documentable, \Altek\Accountant\Recordable,  Eventually;

    protected $fillable = ['name', 'company_id'];
    public function getMainColumn(): string
    {
        return $this->getAttribute('id');
    }
    public function companies(): BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id')->withDefault();
    }
}
