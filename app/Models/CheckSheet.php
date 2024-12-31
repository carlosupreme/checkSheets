<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CheckSheet extends Model
{
    protected $fillable = [
        'equipment_area',
        'equipment_tag',
        'equipment_name',
        'notes',
        'is_published',
        'created_by',
        'updated_by',
        'name'
    ];

    public function items(): HasMany {
        return $this->hasMany(CheckSheetItem::class);
    }

    public function dailyChecks(): HasMany {
        return $this->hasMany(DailyCheck::class);
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
