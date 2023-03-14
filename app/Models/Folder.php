<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Folder extends Model
{
    protected $fillable = ['name', 'company_id', 'composition'];

    public function accessRates()
    {
        return $this->hasMany(AccessRate::class, 'folder_id');
    }
    public function companies(): BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id')->withDefault();
    }

}
