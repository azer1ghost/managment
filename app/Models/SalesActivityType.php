<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class SalesActivityType extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ['name', 'description', 'hard_columns'];

    public array $translatable = ['name', 'description'];

    public static function hardColumns(): array
    {
        return [1 => 'Organization', 'Certificate', 'Activity area', 'Name', 'Address'];
    }
}
