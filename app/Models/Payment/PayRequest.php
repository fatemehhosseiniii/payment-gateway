<?php

namespace App\Models\Payment;

use App\Models\Gateway;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'gateway_id', 'order_code', 'amount', 'remaining_amount', 'status', 'status_note'])]
class PayRequest extends Model
{
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $baseCode = self::query()->max('code');
            if (!$baseCode)
                $baseCode = 100;
            $model->code = $baseCode + 1;
        });
    }
}
