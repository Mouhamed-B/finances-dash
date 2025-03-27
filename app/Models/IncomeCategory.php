<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeCategory extends Model
{
    protected $fillable = ["label"];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function records():HasMany {
        return $this->hasMany(Income::class);
    }
}
