<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @method static insert(array $array)
 * @property mixed role
 * @property mixed name
 * @property mixed surname
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $with = ['role'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function getFullnameAttribute(): string
    {
        return "{$this->getAttribute('name')} {$this->getAttribute('surname')}";
    }

    public function isDeveloper()
    {
       return false;
    }

    public function isAdministrator()
    {
        return false;
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
}
