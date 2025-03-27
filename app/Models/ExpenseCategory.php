<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseCategory extends Model
{
    protected $fillable = ["label"];

    public function records():HasMany {
        return $this->hasMany(Expense::class);
    }
}
