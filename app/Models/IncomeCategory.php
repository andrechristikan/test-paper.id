<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionIncome;

class IncomeCategory extends Model
{
    protected $table = 'income_categories';

    protected $fillable = [
        'name',
    ];

    public function transactionIncome()
    {
        $this->hasMany(TransactionIncome::class, 'income_category_id', 'id');
    }
}
