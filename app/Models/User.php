<?php

namespace App\Models;

use App\Contracts\Auth\MustVerifyPhone;
use App\Traits\GetClassInfo;
use App\Traits\Loger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method static insert(array $array)
 * @property mixed role
 * @property mixed name
 * @property mixed surname
 */
class User extends Authenticatable implements MustVerifyPhone
{
    use HasFactory, Notifiable, SoftDeletes, Loger, GetClassInfo, \App\Traits\Auth\MustVerifyPhone;

    const DIRECTOR = 7;
    const DEVELOPER = 1;

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
        'position_id',
        'official_position_id',
        'department_id',
        'phone_coop',
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

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
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

    public function hasPermission($perm): bool
    {
        if (app()->environment('local')){
            $permissions = config('auth.permissions');
        }else{
            $permissions = explode(',',
                $this->getAttribute('permissions').",".
                $this->getRelationValue('position')->getAttribute('permissions').",".
                $this->getRelationValue('role')->getAttribute('permissions'));
        }

        $uniqPermissions = array_unique($permissions);

        if(in_array('all', $uniqPermissions, true)){
            return true;
        }

        return in_array($perm, $uniqPermissions, true);
    }

    public function getFullnameAttribute(): string
    {
        return "{$this->getAttribute('name')} {$this->getAttribute('surname')}";
    }

    public function getFullnameWithPositionAttribute(): string
    {
        return "{$this->getAttribute('name')} {$this->getAttribute('surname')} ({$this->getRelationValue('position')->getAttribute('name')})";
    }

    public function isDeveloper(): bool
    {
        return $this->getAttribute('role_id') === self::DEVELOPER;
    }

    public function isDirector(): bool
    {
        return $this->getRelationValue('role')->getAttribute('id') == self::DIRECTOR;
    }

    public function isDisabled(): bool
    {
        return !is_null($this->getAttribute('disabled_at'));
    }

    public function isAdministrator(): bool
    {
        return $this->getAttribute('role_id') === 2;
    }

    public function scopeIsActive($query)
    {
        return $query->whereNull('disabled_at');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
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

}
