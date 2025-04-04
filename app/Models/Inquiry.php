<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Enums\InquiryPriority;
use App\Enums\InquiryType;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Inquiry extends Model implements DocumentableInterface, Recordable
{
    use HasFactory, SoftDeletes, Documentable, \Altek\Accountant\Recordable, Eventually;

    // status parameter id
    const STATUS_PARAMETER = 5;
    // option IDs of status parameter
    const ACTIVE = 21;
    const DONE = 22;
    const REJECTED = 23;
    const INCOMPATIBLE = 24;
    const UNREACHABLE = 25;
    // option id of redirected of status parameter
    const REDIRECTED = 40;
    // option IDs of subject parameter
    const NEWCUSTOMER = 68;
    const RECALL = 69;

    protected $fillable = [
        'code',
        'datetime',
        'note',
        'redirected_user_id',
        'company_id',
        'user_id',
        'is_out',
        'department_id',
        'client_id',
        'checking',
        'alarm',
        'next_call_at',
        'type',
        'priority'
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'editable_ended_at' => 'datetime',
        'alarm' => 'datetime',
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

    public function scopeIsReal($query)
    {
        return $query->where('inquiry_id', null);
    }

    public function scopeIsCallCenter($query)
    {
        return $query->whereDepartmentId(Department::CALL_CENTER);
    }

    public function scopeIsSales($query)
    {
        return $query->whereDepartmentId(Department::SALES);
    }
    public function scopeIsTest($query)
    {
        return $query->whereDepartmentId(Department::TEST);
    }

    public function scopeMonthly($query)
    {
        return $query->where('datetime', '>=', now()->firstOfMonth());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
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
        return $this->belongsToMany(Option::class, 'inquiry_parameter', 'inquiry_id', 'value')->withPivot('parameter_id');
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

    public function scopePotentialCustomer($query)
    {
       return $query->wheregetParameter('status')->getAttribute('id') == self::REJECTED;
    }

    public static function generateCustomCode($prefix = 'MGI', $digits = 8): string
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
        return auth()->user()->hasPermission('viewAll-inquiry');
    }

    public static function userCanViewAllDepartment(): bool
    {
        return auth()->user()->hasPermission('viewAllDepartment-inquiry');
    }

    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id')->withDefault();
    }
    public static function types()
    {
        return [
            InquiryType::CLIENT => trans('translates.inquiries.types.1'),
            InquiryType::COLLABORATION => trans('translates.inquiries.types.2'),
            InquiryType::VENDOR => trans('translates.inquiries.types.3'),
            InquiryType::PARTNER => trans('translates.inquiries.types.4'),
            InquiryType::VACANCY => trans('translates.inquiries.types.5'),
        ];
    }
    public static function priorities()
    {
        return [
            InquiryPriority::UNNECESSARY => trans('translates.inquiries.priorities.0'),
            InquiryPriority::LOW => trans('translates.inquiries.priorities.1'),
            InquiryPriority::MEDIUM => trans('translates.inquiries.priorities.2'),
            InquiryPriority::HIGH => trans('translates.inquiries.priorities.3'),
        ];
    }
}