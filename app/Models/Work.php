<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Illuminate\Support\Facades\DB;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Carbon\Carbon;

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
    use HasFactory, SoftDeletes, Documentable, \Altek\Accountant\Recordable, Eventually, \App\Traits\Loger;

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

    const REQUESTNUMBER = 39;

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
        'doc',
        'invoice_company_id',
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

    public function invoiceCompany(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Company::class, 'invoice_company_id')->withDefault();
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'work_parameter')->withPivot('value');
    }

    public function getParameter($id)
    {
        // Get parameter model
        $parameter = $this->parameters()->where('id', $id)->first();

        if (!$parameter) {
            return null;
        }

        // Select tipli parametrlər üçün option mətnini qaytar
        if ($parameter->getAttribute('type') == 'select') {
            return optional(Option::find($parameter->pivot->value))->getAttribute('text');
        }

        $value = optional($parameter->pivot)->value;

        // Vergülü nöqtəyə çevir: PHP riyazi əməliyyatlarda vergüllü ədədi
        // non-numeric kimi qəbul edir, buna görə "1500,00" → "1500.00"
        if ($value !== null && $value !== '') {
            $value = str_replace(',', '.', $value);
        }

        return $value;
    }

    /**
     * Get parameter value as float/numeric from work_parameter table
     *
     * @param int $parameterId
     * @return float|null
     */
    public function getParameterValue(int $parameterId): ?float
    {
        $parameter = $this->parameters()->where('parameters.id', $parameterId)->first();
        
        if (!$parameter || !isset($parameter->pivot->value)) {
            return null;
        }
        
        $value = $parameter->pivot->value;
        return is_numeric($value) ? (float) $value : null;
    }

    /**
     * Set parameter value in work_parameter table
     *
     * @param int $parameterId
     * @param mixed $value
     * @return void
     */
    public function setParameterValue(int $parameterId, $value): void
    {
        // Check if pivot record exists using DB query to avoid relationship issues
        $exists = DB::table('work_parameter')
            ->where('work_id', $this->id)
            ->where('parameter_id', $parameterId)
            ->exists();
        
        if ($exists) {
            DB::table('work_parameter')
                ->where('work_id', $this->id)
                ->where('parameter_id', $parameterId)
                ->update(['value' => $value]);
        } else {
            DB::table('work_parameter')
                ->insert([
                    'work_id' => $this->id,
                    'parameter_id' => $parameterId,
                    'value' => $value
                ]);
        }
        
        // Clear relation cache
        $this->unsetRelation('parameters');
    }

    /**
     * Check if work is fully paid
     *
     * @return bool
     */
    public function isFullyPaid(): bool
    {
        $amount = $this->getParameterValue(self::AMOUNT) ?? 0;
        $paid = $this->getParameterValue(self::PAID) ?? 0;
        
        $vat = $this->getParameterValue(self::VAT) ?? 0;
        $vatPayment = $this->getParameterValue(self::VATPAYMENT) ?? 0;
        
        $illegalAmount = $this->getParameterValue(self::ILLEGALAMOUNT) ?? 0;
        $illegalPaid = $this->getParameterValue(self::ILLEGALPAID) ?? 0;
        
        // Check if main amount is paid
        // If amount > 0, paid must equal amount
        // If amount = 0, check if payment date exists (for zero-amount services or illegal payments)
        $amountPaid = false;
        if ($amount > 0) {
            $amountPaid = abs($paid - $amount) < 0.01;
        } else {
            // Amount is 0 - check if payment date exists (allows marking as paid even with zero amount)
            $amountPaid = $this->paid_at !== null;
        }
        
        // Check if VAT is paid
        // If VAT > 0, VAT payment must equal VAT
        // If VAT = 0, check if VAT payment date exists
        $vatPaid = false;
        if ($vat > 0) {
            $vatPaid = abs($vatPayment - $vat) < 0.01;
        } else {
            // VAT is 0 - check if VAT payment date exists
            $vatPaid = $this->vat_date !== null;
        }
        
        // Check if illegal amount is paid
        $illegalPaidCheck = abs($illegalPaid - $illegalAmount) < 0.01;
        
        return $amountPaid && $vatPaid && $illegalPaidCheck;
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
    /**
     * "2,5", " 3 " kimi dəyərləri rəqəmə çevirir; boş/rəqəm olmayan dəyər üçün null
     */
    public static function toNumber($value): ?float
    {
        if ($value === null) {
            return null;
        }
        $value = str_replace([',', ' '], ['.', ''], trim((string) $value));

        return is_numeric($value) ? (float) $value : null;
    }

    /**
     * Müştərinin bu iş üçün vahid qiymətini və mənbəyini qaytarır:
     * 1) client_service — işin şöbəsi üçün, 2) client_service — ümumi (şöbəsiz).
     * Şöbə sətri var, amma qiyməti boşdursa ümumi qiymət götürülür.
     * clients.price istifadə olunmur — orada sərbəst mətn saxlanılır.
     *
     * @return array{0: float|null, 1: string|null}
     */
    public static function getClientServicePrice($work): array
    {
        if (!$work->client_id) {
            return [null, null];
        }

        $rows = DB::table('client_service')
            ->where('client_id', $work->client_id)
            ->where('service_id', $work->service_id)
            ->get(['department_id', 'amount']);

        if ($work->department_id) {
            foreach ($rows as $row) {
                if ((int) $row->department_id === (int) $work->department_id && ($price = self::toNumber($row->amount)) !== null) {
                    return [$price, 'şöbə qiyməti'];
                }
            }
        }

        foreach ($rows as $row) {
            if ($row->department_id === null && ($price = self::toNumber($row->amount)) !== null) {
                return [$price, 'ümumi xidmət qiyməti'];
            }
        }

        return [null, null];
    }

    public static function getClientServiceAmount($work)
    {
        return self::getClientServicePrice($work)[0];
    }

    // Məbləğ xidmət sayı ilə hesablanan xidmətlər
    const COUNT_SERVICES = [5, 6, 31, 33, 34, 35, 36, 37, 38, 7, 8, 9, 3, 4, 10, 11, 12, 49, 41, 54, 53];
    // Məbləğ GB və əsas vərəq ilə hesablanan xidmətlər
    const GB_SERVICES = [1, 16, 17, 18, 19, 20, 21, 22, 23, 26, 27, 29, 30, 42, 48];
    // Məbləğ GB və QİB əsas vərəq ilə hesablanan xidmətlər
    const QIB_SERVICES = [2];
    // Bu asan imzalarda (əsas vərəq qiyməti olduqda) məbləğ 0 olur
    const ZERO_AMOUNT_ASAN_IDS = [22];
    const VAT_RATE = 0.18;

    /**
     * Müştərinin şöbə/xidmət qiymətinə və GB/xidmət sayına görə məbləği,
     * asan imzanın şirkətinə görə ƏDV-ni hesablayır. Yadda saxlamır.
     *
     * @return array{0: float, 1: float}|null  [məbləğ, ƏDV]; hesablamaq mümkün deyilsə null
     */
    public function calculateAmountAndVat(): ?array
    {
        $price = self::getClientServiceAmount($this);
        if ($price === null) {
            return null;
        }
        $serviceId = (int) $this->service_id;
        $num = fn($value) => self::toNumber($value) ?? 0.0;

        if (in_array($serviceId, self::COUNT_SERVICES)) {
            $count = self::toNumber($this->getParameter(self::SERVICECOUNT));
            if ($count === null) {
                return null;
            }
            $amount = $price * $count;
        } elseif (in_array($serviceId, self::GB_SERVICES) || in_array($serviceId, self::QIB_SERVICES)) {
            $gb = self::toNumber($this->getParameter(self::GB));
            $client = $this->getRelationValue('client');
            if ($gb === null || !$client) {
                return null;
            }
            $deptId = (int) $this->department_id;
            $paper = $num(in_array($serviceId, self::QIB_SERVICES)
                ? $client->getQibPaperForDept($deptId)
                : $client->getMainPaperForDept($deptId));

            if ($paper > 0) {
                if (in_array((int) $this->asan_imza_id, self::ZERO_AMOUNT_ASAN_IDS)) {
                    $amount = 0;
                } else {
                    $mainPage = $num($this->getParameter(self::MAINPAGE));
                    $amount = $price * ($gb - $mainPage) + $paper * $mainPage;
                }
            } else {
                $amount = $price * $gb + $paper;
            }
        } else {
            return null;
        }

        $amount = round($amount, 2);

        // ƏDV asan imzanın şirkətinə görə: şirkət ƏDV-sizdirsə 0, əks halda 18%
        $asanImza = $this->getRelationValue('asanImza');
        $vat = ($asanImza && $asanImza->getKey() && !$asanImza->hasNoVat())
            ? round($amount * self::VAT_RATE, 2)
            : 0.0;

        return [$amount, $vat];
    }

    /**
     * Məbləğ və ƏDV-ni hesablayıb yazır (parametr sətri yoxdursa yaradır).
     */
    public function applyAmountAndVat(): ?array
    {
        $result = $this->calculateAmountAndVat();

        if ($result !== null) {
            [$amount, $vat] = $result;
            $this->setParameterValue(self::AMOUNT, $amount);
            $this->setParameterValue(self::VAT, $vat);
        }

        return $result;
    }
    public function customerEngagement()
    {
        return $this->hasOne(\App\Models\CustomerEngagement::class, 'client_id', 'client_id');
    }
    public function getNeedAttentionAttribute()
    {
        if ($this->paid_at) {
            return false;
        }

        if (!$this->invoiced_date) {
            return false;
        }

        return now()->greaterThan(Carbon::parse($this->invoiced_date)->addDays(30));
    }

}
