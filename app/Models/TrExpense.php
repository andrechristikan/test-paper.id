<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FinanceAccount;
use App\Models\ExpenseCategory;

class TrExpense extends Model
{

    use SoftDeletes;

    protected $table = 'tr_expense';

    protected $fillable = [
        'finance_account_id',
        'expense_category_id',
        'amount'
    ];

    protected $attributes = [
        'amount' => 0,
    ];

    public function financeAccount()
    {
        $this->hasOne(FinanceAccount::class, 'id', 'finance_account_id');
    }

    public function expenseCategory()
    {
        $this->hasOne(ExpenseCategory::class, 'id', 'expense_category_id');
    }
}
