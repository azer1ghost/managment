<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model implements Recordable, DocumentableInterface
{
    use Documentable, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['name', 'voen', 'phone', 'email', 'note', 'is_service', 'supplier_id', 'quality', 'delivery', 'distributor', 'availability', 'certificate', 'support', 'price', 'payment', 'returning', 'replacement'];

    public function getMainColumn(): string
    {
        return $this->getAttribute('name');
    }

    public function setPhoneAttribute($value): ?string
    {
        return $this->attributes['phone'] = phone_cleaner($value);
    }

    public function getPhoneAttribute($value): ?string
    {
        return phone_formatter($value, true);
    }
}
