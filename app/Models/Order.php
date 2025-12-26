<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'client_id', 'transit_customer_id', 'code', 'cmr', 'invoice', 'packing', 'other', 'service', 'amount', 'result', 'status', 'is_paid', 'note'];

    public function clients(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id')->withDefault();
    }

    public function transitCustomer(): BelongsTo
    {
        return $this->belongsTo(TransitCustomer::class, 'transit_customer_id')->withDefault();
    }

    public static function generateCustomCode($prefix = 'TRN', $digits = 8): string
    {
        do {
            $code = $prefix . str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
            if (! self::select('code')->whereCode($code)->exists()) {
                break;
            }
        } while (true);

        return $code;
    }

    public static function statuses()
    {
        return [1 => 1, 2, 3, 4];
    }
}
