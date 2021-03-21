<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TrExpense;

class ExpenseCategory extends Model
{
    protected $table = 'expense_category';

    protected $fillable = [
        'name',
    ];

    public function trExpense()
    {
        $this->hasMany(TrExpense::class, 'expense_category_id', 'id');
    }
}
