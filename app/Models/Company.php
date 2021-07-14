<?php

namespace App\Models;

use App\Models\Inquiry\ContactTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static select(string[] $array)
 * @method static create($validated)
 * @method static insert(array $array)
 * @method static where(string $string, string $string1, int $int)
 * @method static whereNotIn(string $string, int[] $array)
 */
class Company extends Model
{
    protected $fillable = ['name', 'logo', 'website', 'mail', 'phone', 'mobile', 'address', 'about'];

    public function inquiryContactTypes(): BelongsToMany
    {
        return $this->belongsToMany(ContactTypes::class);
    }
}
