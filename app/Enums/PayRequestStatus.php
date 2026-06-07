<?php

namespace App\Enums;

enum PayRequestStatus: int
{
    case Pending = 0;
    case Success = 1;
    case Expired = 2;
    case Fail = 3;
    case PartialFail = 4;

    public function key(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Success => 'Success',
            self::Expired => 'Expired',
            self::Fail => 'Fail',
            self::PartialFail => 'PartialFail'
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('statuses.transaction-status.pending'),
            self::Success => __('statuses.transaction-status.success'),
            self::Expired => __('statuses.transaction-status.expired'),
            self::Fail => __('statuses.transaction-status.fail'),
            self::PartialFail => __('statuses.transaction-status.partial-fail')
        };
    }


    public function toArray(): array
    {
        return [
            'key' => $this->key(),
            'label' => $this->label()
        ];
    }

}
