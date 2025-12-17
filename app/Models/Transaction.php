<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Relations\BelongsTo, SoftDeletes};

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'account_id',
        'source',
        'amount',
        'currency',
        'type',
        'method',
        'status',
        'note',
        'client_id',
        'transaction_date',
    ];

    //types
    const EXPENSE = 0;
    const INCOME = 1;

    //statuses
    const SUCCESSFUL = 0;
    const RETURNED = 1;

    use SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class)->withDefault();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    /**
     * @param $type
     * @param $amount
     * @param $company
     * @param string $currency
     * @param int $account
     * @param $source
     * @param $method
     * @param $note
     * @return mixed
     */
    public static function addTransaction($user = null, $type, $amount, $company = null, string $currency = 'AZN', $account = null, $source = null, $status = '1', $method = null, $note)
    {
        return self::create([
            'user_id' => $user,
            'type' => $type,
            'amount' => $amount,
            'company_id' => $company,
            'account_id' => $account,
            'source' => $source,
            'currency' => $currency,
            'method' => $method,
            'status' => $status,
            'note' => $note,
        ]);
    }

    public static function statuses()
    {
        return [1 => 1, 2];
    }

    public static function types()
    {
        return [1 => 1, 2];
    }

    public static function methods()
    {
        return [1 => 1, 2];
    }
}
