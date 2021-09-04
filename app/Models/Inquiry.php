<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Inquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'datetime', 'note', 'redirected_user_id', 'company_id', 'user_id'
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        return $user->isDeveloper() || $user->isAdministrator() || $user->role->hasPermission('viewAll-inquiry');
    }

}






//protected static function booted()
//{
//        static::created(function ($inquiry) {
//            //
//        });

//        static::updated(function ($inquiry) {
//
//            $namespace = explode('\\', static::class);
//            $model = last($namespace);
//            $action = "updated";
//
//            $log = [
//                'model'   => $model,
//                'id'      => $inquiry->id,
//                'user_id' => auth()->id(),
//                'action'  => $action,
//                'message' => "The {$model} was {$action}",
//                'old'     => $inquiry,
//                'changes' => $inquiry->getChanges(),
//                'created_at' => now()
//            ];
//
//             Log::channel('daily')->info(json_encode($log));
//        });

//        static::softDeleted(function ($inquiry) {
//            //
//        });
//
//        static::forceDeleted(function ($inquiry) {
//            //
//        });
//
//        static::restored(function ($inquiry) {
//            //
//        });
//}
