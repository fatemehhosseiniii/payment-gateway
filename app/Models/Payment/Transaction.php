<?php

namespace App\Models\Payment;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pay_request_id', 'amount', 'trac_code', 'refid', 'transaction_id', 'pay_date', 'pay_status', 'status'])]
class Transaction extends Model
{
    protected function casts(): array
    {
        return [
            'status' => TransactionStatus::class
        ];
    }

    public function payRequest(): BelongsTo
    {
        return $this->belongsTo(PayRequest::class);
    }


    protected static function boot()
    {
        parent::boot();

        self::updating(function ($model) {
            $payRequest = $model->payRequest;
            if($payRequest){
//                if ($model->status )
            }
        });
    }

}
