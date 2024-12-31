<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckSheetItem extends Model
{
    protected $fillable = [
        'properties',
        'order',
        'check_sheet_id',
    ];

    protected $casts = [
        'properties' => 'json',
    ];

    public function checkSheet(): BelongsTo {
        return $this->belongsTo(CheckSheet::class);
    }
}
