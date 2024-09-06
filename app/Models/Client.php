<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client  extends Authenticatable implements DocumentableInterface, Recordable
{
    use SoftDeletes, Notifiable, HasFactory, Documentable, \Altek\Accountant\Recordable, Eventually;

    const LEGAL = 0;
    const PHYSICAL  = 1;
    const FOREIGNPHYSICAL  = 2;
    const FOREIGNLEGAL  = 3;
    const ACTIVE = 1;
    const PASSIVE = 0;


    protected $fillable = [
        'fullname',
        'phone1',
        'email1',
        'address1',
        'voen',
        'password',
        'position',
        'type',
        'send_sms',
        'active',
        'detail',
        'protocol',
        'document_type',
        'client_id',
        'user_id',
        'price',
        'birthday',
        'sector',
        'main_paper',
        'qibmain_paper',
        'ordering',
        'channel',
        'deleted_items',
        'payment_method'
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(__CLASS__, 'client_id');
    }

    public function scopeLegal($query)
    {
        return $query->whereType(self::LEGAL);
    }

    public function scopePhysical($query)
    {
        return $query->whereType(self::PHYSICAL);
    }
    public function scopeForeignPhysical($query)
    {
        return $query->whereType(self::FOREIGNPHYSICAL);
    }
    public function scopeForeignLegal($query)
    {
        return $query->whereType(self::FOREIGNLEGAL);
    }

    public function getMainColumn(): string
    {
        return $this->getAttribute('fullname');
    }

    public static function userCanViewAll(): bool
    {
        return auth()->user()->hasPermission('viewAll-client');
    }

    public static function userCannotViewAll(): bool
    {
        return !self::userCanViewAll();
    }

    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sales_clients_relationship');
    }
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'clients_companies_relationship');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'client_id')->withDefault();
    }
    public function setPhone1Attribute($value): ?string
    {
        return $this->attributes['phone1'] = phone_cleaner($value);
    }

    public function getPhone1Attribute($value): ?string
    {
        return phone_formatter($value, true);
    }

    public function getFullnameWithVoenAttribute($value): ?string
    {
        foreach ($this->companies as $company) {
            $companies[] = $company->getAttribute('name');
        }
        $company = implode(',', $companies);

        return "{$this->getAttribute('fullname')} ({$this->getAttribute('voen')}) ($company)";
    }

    public function users():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function references():BelongsTo
    {
        return $this->belongsTo(User::class, 'reference')->withDefault();
    }

    public function engagement():HasMany
    {
        return $this->hasMany(CustomerEngagement::class, 'client_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'client_service')
            ->withPivot('amount');
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'client_id');
    }

    public static function channels(): array
    {
        return [1 => 1, 2, 3, 4, 5, 6, 7, 8, 9];
    }
    public static function paymentMethods(): array
    {
        return [1 => 1, 2, 3];
    }

}