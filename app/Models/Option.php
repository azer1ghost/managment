<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Option extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['text'];

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class)->withPivot('company_id');
    }

    public function inquires(): BelongsToMany
    {
        return $this->belongsToMany(Inquiry::class, 'inquiry_parameter')->withPivot('parameter_id');
    }
}
