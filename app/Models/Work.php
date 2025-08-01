<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Illuminate\Support\Facades\DB;
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

    const PLANNED = 1;
    const PENDING = 2;
    const STARTED = 3;
    const INJECTED = 4;
    const RETURNED = 5;
    const ARCHIVE = 6;

    const DONE = 7;

    const REJECTED = 8;

    const GB = 17;
    const CODE = 18;
    const SERVICECOUNT = 20;
    const AMOUNT = 33;
    const ILLEGALAMOUNT = 38;
    const VAT = 34;
    const PAID = 35;
    const ILLEGALPAID = 37;
    const VATPAYMENT = 36;
    const MAINPAGE = 48;
    const QIBPAYMENT = 50;
    const QIBAMOUNT = 55;

    protected $fillable = [
        'code',
        'declaration_no',
        'transport_no',
        'detail',
        'creator_id',
        'sorter_id',
        'operator_id',
        'analyst_id',
        'user_id',
        'department_id',
        'asan_imza_id',
        'service_id',
        'client_id',
        'custom_asan',
        'custom_client',
        'status',
        'destination',
        'payment_method',
        'datetime',
        'created_at',
        'verified_at',
        'paid_at',
        'vat_date',
        'entry_date',
        'injected_at',
        'returned_at',
        'resume_date',
        'bank_charge',
        'invoiced_date',
        'mark',
        'painted',
        'doc'
    ];

    protected $dates = ['datetime', 'verified_at', 'paid_at', 'vat_date', 'invoiced_date', 'entry_date', 'injected_at', 'returned_at', 'resume_date'];

    public function getMainColumn(): string
    {
        return $this->getRelationValue('department')->getAttribute('name');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id')->withDefault();
    }
    public function sorter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sorter_id')->withDefault();
    }
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id')->withDefault();
    }
    public function analyst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analyst_id')->withDefault();
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
        return [1 => 1, 2, 3, 4, 5, 6, 7, 8, 9 ];
    }

    public static function destinations(): array
    {
        return [1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
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

    public function scopePlanned($query)
    {
        return $query->where('status', self::PLANNED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::PENDING);
    }
    public static function getClientServiceAmount($work)
    {
        $clientService = DB::table('client_service')
            ->where('client_id', $work->client_id)
            ->where('service_id', $work->service_id)
            ->first();

        if ($clientService) {
            return $clientService->amount;
        }

        return null;
    }
    public function customerEngagement()
    {
        return $this->hasOne(\App\Models\CustomerEngagement::class, 'client_id', 'client_id');
    }

}
