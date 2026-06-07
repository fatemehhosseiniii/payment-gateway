<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['title', 'key', 'params', 'max_amount', 'is_active'])]
class Gateway extends Model
{
    use SoftDeletes, HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'params' => 'array',
        ];
    }
}
