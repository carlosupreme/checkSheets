<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyCheck extends Model
{
    protected $fillable = [
        'check_sheet_id',
        'operator_id',
        'operator_name',
        'checked_at',
        'operator_signature',
        'supervisor_signature',
        'notes',
    ];


    public function checkSheet(): BelongsTo {
        return $this->belongsTo(CheckSheet::class);
    }

    public function dailyCheckItems(): HasMany {
        return $this->hasMany(DailyCheckItem::class);
    }
}
