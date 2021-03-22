<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionExpense;

class ExpenseCategory extends Model
{
    protected $table = 'expense_categories';

    protected $fillable = [
        'name',
    ];

    public function transactionExpense()
    {
        $this->hasMany(TransactionExpense::class, 'expense_category_id', 'id');
    }
}
