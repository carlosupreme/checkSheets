<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCheckItem extends Model
{
    protected $fillable = [
        'daily_check_id',
        'item',
        'notes',
        'check_status_id'
    ];

    protected $casts = [
        "item" => "json"
    ];

    public function dailyCheck(): BelongsTo {
        return $this->belongsTo(DailyCheck::class);
    }

    public function checkStatus(): BelongsTo {
        return $this->belongsTo(CheckStatus::class);
    }
}
