<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo, SoftDeletes};
use Illuminate\Support\Collection;

class Work extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'detail',
        'user_id',
        'company_id',
        'department_id',
        'service_id',
        'model',
        'model_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function clientType(): Collection
    {
        $typeId = $this->getAttribute('client_id');

        return $this->getAttribute('model') == 'client' ?
            Client::find($typeId) :
            CustomerCompany::find($typeId);
    }

    public function clients(): Collection
    {
        return $this->getAttribute('model') == 'client' ?
            Client::get(['id', 'name']) :
            CustomerCompany::get(['id', 'name']);
    }
}
