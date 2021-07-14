<?php

namespace App\Models\Inquiry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @method static insert(array[] $array)
 */
class ContactTypes extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $table = 'inquiry_contact_types';
    use HasFactory;

}
