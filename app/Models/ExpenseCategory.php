<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseCategory extends Model
{
    protected $fillable = ["label"];

    public function records():HasMany {
        return $this->hasMany(Expense::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
