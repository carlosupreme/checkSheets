<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemAlert extends Model
{
    protected $table = 'alerts';

    protected $fillable = [
        'check_sheet_item_id',
        'check_status_id',
        'custom',
        'contador'
    ];

    public function checkSheetItem(): BelongsTo {
        return $this->belongsTo(CheckSheetItem::class);
    }

    public function checkStatus(): BelongsTo {
        return $this->belongsTo(CheckStatus::class);
    }
}
