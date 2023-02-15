<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomerSatisfaction extends Model
{
    const NAME = 1;
    const PHONE = 2;
    const FULLNAME = 7;
    const REQNUM = 39;


    protected $fillable = ['satisfaction_id', 'rate', 'price_rate', 'note', 'detail'];

    public function satisfaction(): BelongsTo
    {
        return $this->belongsTo(Satisfaction::class)->withDefault();
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'customer_satisfaction_parameter')->withPivot('value');
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

    public static function rates() :array
    {
        return [1 => 1, 2, 3, 4, 5];
    }

}
