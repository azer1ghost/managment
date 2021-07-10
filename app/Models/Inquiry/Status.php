<?php

namespace App\Models\Inquiry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array[] $array)
 */
class Status extends Model
{
    protected $table = 'inquiry_statuses';
    use HasFactory;
}
