<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class CheckStatus extends Model
{
    protected $fillable = [
        'name',
        'color',
        'icon',
        'description',
        'created_by',
        'updated_by'
    ];

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }


    //TODO:  Si se borra, reemplazar todos los daily_check_items que tenian este id por su valor en texto representando el icono;


    public function dailyCheckItems(): HasMany {
        return $this->hasMany(DailyCheckItem::class);
    }

    public function updatedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
