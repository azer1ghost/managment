<?php

namespace App\Models\Inquiry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array[] $array)
 */
class Subject extends Model
{
    protected $table = 'inquiry_subjects';
    use HasFactory;
}
