<?php

namespace App\Models;

use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use App\Traits\Loger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Inquiry extends Model implements DocumentableInterface
{
    use HasFactory, SoftDeletes, Documentable;

    // status parameter id
    const STATUS_PARAMETER = 5;
    // option id of done of status parameter
    const DONE = 22;
    // option id of redirected of status parameter
    const REDIRECTED = 40;

    protected $fillable = [
        'code', 'datetime', 'note', 'redirected_user_id', 'company_id', 'user_id', 'is_out'
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'editable_ended_at' => 'datetime'
    ];

    public function getColumns(): Collection
    {
       return collect($this->getFillable());
    }

    public function scopeIsReal($query)
    {
        return $query->where('inquiry_id', null);
    }

    public function scopeMonthly($query)
    {
        return $query->where('datetime', '>=', now()->firstOfMonth());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function backups(): HasMany
    {
        return $this->hasMany(__CLASS__);
    }

    public function scopeWithoutBackups($query)
    {
        return $query->whereNull('inquiry_id');
    }

    public function editableUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_can_edit_inquiries')->withPivot('editable_ended_at');
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'inquiry_parameter', 'value')->withPivot('parameter_id');
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class)->withPivot('value');
    }

    public function getParameter($name)
    {
        // Get parameter model
        $parameter = $this->parameters()->where('name', $name)->first();

        return $parameter ?
            // Check type of parameter -> if type is "select" return option value / else return pivot value
             $parameter->getAttribute('type') == 'select' ?
                Option::find($parameter->pivot->value) :
                $parameter->pivot:
         null;
    }

    public function getWasDoneAttribute(): bool
    {
       return optional($this->getParameter('status'))->getAttribute('id') == self::DONE;
    }

    public static function generateCustomCode($prefix = 'MG', $digits = 8): string
    {
        do {
            $code = $prefix . str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            if (! self::select('code')->withTrashed()->whereCode($code)->exists()) {
                break;
            }
        } while (true);

        return $code;
    }

    public static function userCanViewAll(): bool
    {
        $user = auth()->user();
        return $user->isDeveloper() || $user->isAdministrator() || $user->hasPermission('viewAll-inquiry');
    }

    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'logable');
    }

    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }
}