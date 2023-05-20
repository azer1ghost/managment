<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{

    protected $fillable = ['supplier_id', 'quality', 'delivery', 'distributor', 'availability', 'certificate', 'support', 'price', 'payment', 'returning', 'replacement'];
}
