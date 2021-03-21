<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FinanceAccount;
use App\Models\IncomeCategory;


class TrIncome extends Model
{
    use SoftDeletes;

    protected $table = 'tr_income';

    protected $fillable = [
        'finance_account_id',
        'income_category_id',
        'amount'
    ];

    protected $attributes = [
        'amount' => 0,
    ];

    public function financeAccount()
    {
        $this->hasOne(FinanceAccount::class, 'finance_account_id', 'id');
    }

    public function incomeCategory()
    {
        $this->hasOne(IncomeCategory::class, 'income_category_id', 'id');
    }

}
