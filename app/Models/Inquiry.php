<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Inquiry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code', 'datetime',
        'client', 'fullname', 'phone',
        'subject', 'kind', 'contact_method', 'source', 'operation', 'status',
        'note', 'redirected_user_id', 'company_id', 'user_id'
    ];

    protected $casts = [
        'datetime' => 'datetime'
    ];

    public function getColumns(): Collection
    {
       return collect($this->getFillable());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function backups(): HasMany
    {
        return $this->hasMany(__CLASS__);
    }

    public function getParameter($data)
    {
       return Cache::remember("parameter_{$this->subject}", 60, fn () =>
             Parameter::select(['name'])->find($this->{$data})->getAttribute('name')
        );
    }
}

//protected static function booted()
//{
//        static::created(function ($inquiry) {
//            //
//        });

//        static::updated(function ($inquiry) {
//
//            $namespace = explode('\\', static::class);
//            $model = last($namespace);
//            $action = "updated";
//
//            $log = [
//                'model'   => $model,
//                'id'      => $inquiry->id,
//                'user_id' => auth()->id(),
//                'action'  => $action,
//                'message' => "The {$model} was {$action}",
//                'old'     => $inquiry,
//                'changes' => $inquiry->getChanges(),
//                'created_at' => now()
//            ];
//
//             Log::channel('daily')->info(json_encode($log));
//        });

//        static::softDeleted(function ($inquiry) {
//            //
//        });
//
//        static::forceDeleted(function ($inquiry) {
//            //
//        });
//
//        static::restored(function ($inquiry) {
//            //
//        });
//}
