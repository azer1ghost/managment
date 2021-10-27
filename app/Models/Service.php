<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ['name','detail','company_id','department_id'];

    public array $translatable = ['name'];


    public function company() {
        return $this -> belongsTo(Company::class);
    }

    public function department() {
        return $this -> belongsTo(Department::class);
    }

}
