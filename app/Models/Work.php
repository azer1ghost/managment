<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    SoftDeletes};

/**
 * @method static select(string[] $array)
 */
class Work extends Model implements DocumentableInterface, Recordable
{
    use HasFactory, SoftDeletes, Documentable, \Altek\Accountant\Recordable, Eventually;

    const PENDING = 1;
    const STARTED = 2;
    const DONE = 3;
    const REJECTED = 4;

    const SATISFIED = 1;
    const UNSATISFIED = 2;
    const UNKNOWN = 3;

    const GB = 17;
    const CODE = 18;
    const AMOUNT = 33;
    const ILLEGALAMOUNT = 38;
    const VAT = 34;
    const PAID = 35;
    const ILLEGALPAID = 37;
    const VATPAYMENT = 36;

    protected $fillable = [
        'detail',
        'creator_id',
        'user_id',
        'department_id',
        'asan_imza_id',
        'service_id',
        'client_id',
        'custom_asan',
        'custom_client',
        'status',
        'payment_method',
        'datetime',
        'created_at',
        'verified_at',
        'paid_at',
        'vat_date',
    ];

    protected $dates = ['datetime', 'verified_at', 'paid_at', 'vat_date'];

    public function getMainColumn(): string
    {
        return $this->getAttribute('code');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id')->withDefault();
    }

    public function hours(): HasMany
    {
        return $this->hasMany(WorkStatusLog::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function asanImza(): BelongsTo
    {
        return $this->belongsTo(AsanImza::class)->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class)->withDefault();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'work_parameter')->withPivot('value');
    }

    public function getParameter($id)
    {
        // Get parameter model
        $parameter = $this->parameters()->where('id', $id)->first();

        return $parameter ?
            // Check type of parameter -> if type is "select" return option value / else return pivot value
            $parameter->getAttribute('type') == 'select' ?
                optional(Option::find($parameter->pivot->value))->getAttribute('text') :
                optional($parameter->pivot)->value :
            null;
    }

    public static function statuses(): array
    {
        return [1 => 1, 2, 3, 4];
    }

    public static function paymentMethods(): array
    {
        return [1 => 1, 2, 3];
    }

    public static function userCanViewAll(): bool
    {
        $user = auth()->user();
        return $user->isDeveloper() || $user->hasPermission('viewAll-work');
    }

    public static function userCannotViewAll(): bool
    {
        return !self::userCanViewAll();
    }

    public static function userCanViewDepartmentWorks(): bool
    {
        return auth()->user()->hasPermission('viewAllDepartment-work');
    }

    public static function userCannotViewDepartmentWorks(): bool
    {
        return !self::userCanViewDepartmentWorks();
    }

    public function isDone(): bool
    {
        return $this->getAttribute('status') === self::DONE;
    }

    public function hasAsanImza(): bool
    {
        return $this->getRelationValue('service')->hasAsanImza();
    }

    public function scopeIsVerified($query)
    {
        return $query->whereNotNull('verified_at')->where('status', '!=', self::REJECTED);
    }
    public function scopeIsPaid($query)
    {
        return $query->whereNotNull('paid_at')->where('status', '!=', self::REJECTED);
    }

    public function scopeIsRejected($query)
    {
        return $query->where('status', self::REJECTED);
    }

    public function scopeWorksDone($query)
    {
        return $query->where('status', self::DONE);
    }

    public static function generateCustomCode($prefix = 'MGW', $digits = 8): string
    {
        do {
            $code = $prefix . str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            if (! self::select('code')->withTrashed()->whereCode($code)->exists()) {
                break;
            }
        } while (true);

        return $code;
    }
}
