<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Model;

class Necessary extends Model implements DocumentableInterface, Recordable

{
    use Documentable, \Altek\Accountant\Recordable,  Eventually;
    protected $fillable = ['name', 'detail'];
    public function getMainColumn(): string
    {
        return $this->getAttribute('id');
    }
}
