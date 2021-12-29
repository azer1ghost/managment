<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Certificate extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ['name', 'detail', 'organization_id'];

    public array $translatable = ['name', 'detail'];

//    public function organizations() :BelongsTo
//    {
//        $this->belongsTo(Organization::class)->withDefault();
//    }
}
