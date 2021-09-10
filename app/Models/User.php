<?php

namespace App\Models;

use App\Contracts\Auth\MustVerifyPhone;
use App\Traits\Loger;
use Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    use HasFactory, Notifiable, SoftDeletes, Loger, \App\Traits\Auth\MustVerifyPhone;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        'password',
        'verify_code',
        'permissions'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'role_id' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class)->withDefault();
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class)->withDefault();
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

    public function isDeveloper(): bool
    {
        return $this->getAttribute('role_id') === 1;
    }

    public function isAdministrator(): bool
    {
        return $this->getAttribute('role_id') === 2;
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

    public function setPasswordAttribute($value): string
    {
        return $this->attributes['password'] = Hash::make($value);
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
}
