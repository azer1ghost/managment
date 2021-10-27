<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Work extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','detail','user_id','company_id','department_id'];


    public function user() {
        return $this -> belongsTo(User::class);
    }

    public function company() {
        return $this -> belongsTo(Company::class);
    }

    public function department() {
        return $this -> belongsTo(Department::class);
    }

}
