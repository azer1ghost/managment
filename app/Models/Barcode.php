<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Barcode extends Model
{
    use HasFactory, SoftDeletes;

    // status parameter id
    const STATUS_PARAMETER = 5;
    // option IDs of status parameter
    const ACTIVE = 21;
    const DONE = 22;
    const REJECTED = 23;
    const INCOMPATIBLE = 24;
    const UNREACHABLE = 25;
    const REDIRECTED = 26;

    protected $fillable = [
        'code',
        'note',
        'company_id',
        'user_id',
        'client_id',
        'mediator_id',
    ];

    protected $touches = ['parameters'];

    public function getMainColumn(): string
    {
        return $this->getAttribute('code');
    }

    public function getColumns(): Collection
    {
        return collect($this->getFillable());
    }

    public function scopeMonthly($query)
    {
        return $query->where('created_at', '>=', now()->firstOfMonth());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function mediator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mediator_id')->withDefault();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'barcode_parameter', 'barcode_id', 'value')->withPivot('parameter_id');
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

    public function getParameterById($id)
    {
        // Get parameter model
        $parameter = $this->parameters()->find($id);

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

    public static function userCanViewAll(): bool
    {
        return auth()->user()->hasPermission('viewAll-barcode');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(SalesClient::class, 'client_id')->withDefault();
    }

}
