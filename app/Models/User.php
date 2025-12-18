<?php

namespace App\Models;

use Altek\Accountant\Contracts\Identifiable;
use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Contracts\Auth\MustVerifyPhone;
use App\Traits\Documentable;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\Types\Self_;

/**
 * @method static insert(array $array)
 * @property mixed role
 * @property mixed name
 * @property mixed surname
 */
class
User extends Authenticatable implements MustVerifyPhone, Recordable
{
    use HasFactory, Notifiable, SoftDeletes, Documentable, GetClassInfo, \App\Traits\Auth\MustVerifyPhone, \Altek\Accountant\Recordable, Eventually, \App\Traits\Loger;

    const DIRECTOR = 7;
    const DEVELOPER = 1;
    const CHIEF_DEVELOPER = 1;

    const TRANSIT = 9;


    protected $fillable = [
        'name',
        'surname',
        'avatar',
        'gender',
        'father',
        'serial_pattern',
        'serial',
        'fin',
        'birthday',
        'started_at',
        'position_id',
        'voen',
        'rekvisit',
        'department_id',
        'phone_coop',
        'balance',
        'phone',
        'country',
        'city',
        'address',
        'company_id',
        'role_id',
        'email_coop',
        'email',
        'default_lang',
        'password',
        'verify_code',
        'permissions',
        'disabled_at',
        'is_partner',
        'order',
        'gross',
        'bonus',
        'coefficient',
        'qib_coefficient',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role_id' => 'integer',
        'email_verified_at' => 'datetime',
        'gender' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();
        static::updating(function($model){
            if($model->isDirty('department_id')){
                $model->position_id = null;
            }
        });
    }

    public static function types(): array
    {
        return [
            1 => 'employees',
            2 => 'partners',
            3 => 'all'
        ];
    }

    public static function status(): array
    {
        return [
            1 => 'active',
            2 => 'deactivate',
            3 => 'all'
        ];
    }

    public function salesActivityUsers(): HasMany
    {
        return $this->hasMany(SalesActivity::class);
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }
    public function parameters()
    {
        return $this->belongsToMany(Parameter::class, 'work_parameter')->withPivot('value');
    }
    public function logistics(): HasMany
    {
        return $this->hasMany(Logistics::class);
    }

    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'sales_clients_relationship', 'user_id', 'client_id');
    }
    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'coordinators_clients_relationship');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class)->withDefault();
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class)->withDefault();
    }

    public function officialPosition(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'official_position_id')->withDefault();
    }

    public function compartment(): BelongsTo
    {
        if($this->isDirector()){
            return $this->company();
        }
        return $this->department();
    }

    public function hasPermission(...$perms)
    {
        $isAuthorized = false;

        if (app()->environment('local')){
            $permissions = config('auth.permissions');
        }else{
            $permissions = explode(',',
                $this->getAttribute('permissions').",".
                $this->getRelationValue('position')->getAttribute('permissions').",".
                $this->getRelationValue('department')->getAttribute('permissions').",".
                $this->getRelationValue('role')->getAttribute('permissions'));
        }

        $uniqPermissions = array_unique($permissions);

        if(in_array('all', $uniqPermissions, true) || $this->isDeveloper()){
            $isAuthorized = true;
        }

        if(array_intersect($uniqPermissions, $perms)){
            $isAuthorized = true;
        }

        if ($perms[0] == ""){
            $isAuthorized = false;
        }

        return $isAuthorized;
    }

    public function getFullnameAttribute(): string
    {
        return "{$this->getAttribute('name')} {$this->getAttribute('surname')}";
    }

    public function getFullnameWithPositionAttribute(): string
    {
        if ($this->isDirector()){
            $position = $this->getRelationValue('role')->getAttribute('name');
        }else{
            $position = $this->getRelationValue('position')->getAttribute('name') ?? trans('translates.users.titles.employee') ;
        }
        return "{$this->getAttribute('name')} {$this->getAttribute('surname')} ({$position})";
    }

    public function isDeveloper(): bool
    {
        return $this->getAttribute('role_id') === self::DEVELOPER;
    }

    public function isSales(): bool
    {
        return $this->getAttribute('department_id') === 7;
    }
    public function isCoordinator(): bool
    {
        return $this->getAttribute('department_id') === 22;
    }

    public function isNotDeveloper(): bool
    {
        return !self::isDeveloper();
    }

    public function isDirector(): bool
    {
        return $this->getRelationValue('role')->getAttribute('id') == self::DIRECTOR;
    }
    public function isQualityControl(): bool
    {
        return $this->getAttribute('department_id') == 25;
    }

    public function isDisabled(): bool
    {
        return !is_null($this->getAttribute('disabled_at'));
    }

    public function isTransitCustomer(): bool
    {
        return $this->getAttribute('role_id') == self::TRANSIT;
    }

    public function isDepartmentChief(): bool
    {
        return $this->hasPermission('department-chief');
    }

    public function scopeIsActive($query)
    {
        return $query->where('role_id', '!=', self::TRANSIT)->whereNull('disabled_at');
    }

    public function chiefReport(): HasOne
    {
        return $this->hasOne(Report::class, 'chief_id');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function barcodes(): HasMany
    {
        return $this->hasMany(Barcode::class);
    }

    public function editableInquiries(): BelongsToMany
    {
        return $this->belongsToMany(Inquiry::class, 'user_can_edit_inquiries')->withPivot('editable_ended_at');
    }

    public function canEditInquiry(Inquiry $inquiry): bool
    {
        if ($query = $this->editableInquiries()->withTrashed()->find($inquiry->getAttribute('id'))){
            return $query->pivot->getAttribute('editable_ended_at') > now();
        }

       return false;
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function defaults(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'user_default')->withPivot('value');
    }

    public function getUserDefault($parameter = null): ?string
    {
        $params = [];
        foreach (auth()->user()->getRelationValue('defaults') as $param){
            $params[$param->name] = $param->pivot->value;
        }
        return $params[$parameter] ?? null;
    }

    public function getDefault($column)
    {
       return optional($this->defaults()->where('column', $column)->first())->getAttribute('value');
    }

    public function setPhoneCoopAttribute($value): ?string
    {
        return $this->attributes['phone_coop'] = phone_cleaner($value);
    }

    public function setPhoneAttribute($value): ?string
    {
        return $this->attributes['phone'] = phone_cleaner($value);
    }

    public function getPhoneCoopAttribute($value): ?string
    {
        return phone_formatter($value, true);
    }

    public function getPhoneAttribute($value): ?string
    {
        return phone_formatter($value, true);
    }

    public function getProtectedPhoneAttribute(): ?string
    {
       return str_pad(substr($this->getAttribute('phone'), -4), strlen($this->getAttribute('phone')), '*', STR_PAD_LEFT);
    }

    public function definedTasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function referral(): HasOne
    {
        return $this->hasOne(Referral::class);
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function setDepartmentIdAttribute($value)
    {
        return $this->attributes['department_id'] = $this->isDirector() ? null : $value;
    }

    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class);
    }

    public function deviceFcmTokens(): array
    {
        return $this->devices()->pluck('fcm_token')->toArray();
    }

    public static function serialPattern(): array
    {
        return  [
            'AZE' => 'AZE',
            'AA' => 'AA',
            'MYI' => 'MYI',
            'DYI' => 'DYI',
        ];
    }

    public function getMainColumn(): string
    {
        return $this->getAttribute('name');
    }

    public function chats() :HasMany
    {
        return $this->hasMany(Chat::class, 'from')->orderBy('is_read');
    }
    public function employeeRegistrations()
    {
        return $this->hasMany(EmployeeRegistration::class);
    }

    public function getFullNameWithDepartmentAttribute(): string
    {
        $department = $this->getRelationValue('department')->getAttribute('name');

        return "{$this->getAttribute('name')} {$this->getAttribute('surname')} ({$department})";
    }
}
