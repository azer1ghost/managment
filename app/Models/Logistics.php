<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logistics extends Model
{
    use SoftDeletes;

    protected $fillable = ['reg_number', 'user_id', 'service_id', 'reference_id', 'client_id', 'transport_type', 'status', 'datetime', 'paid_at'];
    protected $dates = ['datetime', 'paid_at'];

    const PICKEDUP = 1;
    const INPROCESS = 2;
    const ONTHEWAY = 3;
    const ARRIVED = 4;
    const ROAD = 1;
    const AIR = 2;
    const WATER = 3;
    const RAIL = 4;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function reference(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class)->withDefault();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class,'client_id')->withDefault();
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'logistics_parameter')->withPivot('value');
    }
    public function getParameter($id)
    {
        $parameter = $this->parameters()->where('id', $id)->first();

        return $parameter ?
            $parameter->getAttribute('type') == 'select' ?
                optional(Option::find($parameter->pivot->value))->getAttribute('text') :
                optional($parameter->pivot)->value :
            null;
    }

    public static function statuses(): array
    {
        return [1 => 1, 2, 3, 4];
    }

    public static function transportTypes(): array
    {
        return [1 => 1, 2, 3, 4];
    }

}
