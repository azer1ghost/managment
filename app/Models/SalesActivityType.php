<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesActivityType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'hard_columns'];

    public static function hard_columns(): array
    {
        return ['A', 'B', 'C', 'D'];
    }
}
