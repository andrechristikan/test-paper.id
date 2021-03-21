<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TrIncome;

class IncomeCategory extends Model
{
    protected $table = 'income_categories';

    protected $fillable = [
        'name',
    ];

    public function trIncome()
    {
        $this->hasMany(TrIncome::class, 'income_category_id', 'id');
    }
}
