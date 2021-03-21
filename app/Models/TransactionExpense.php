<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FinanceAccount;
use App\Models\ExpenseCategory;
use Auth;

class TransactionExpense extends Model
{

    use SoftDeletes;

    protected $table = 'transaction_expense';

    protected $fillable = [
        'finance_account_id',
        'expense_category_id',
        'amount'
    ];

    protected $attributes = [
        'amount' => 0,
    ];

    public function scopeGetByUserId($query)
    {
        $user = Auth::guard()->user();
        return $query
            ->select(
                'transaction_expense.finance_account_id as finance_account_id', 
                'transaction_expense.expense_category_id as category_id', 
                'expense_categories.name as category_name',
                'finance_accounts.user_id as user_id', 
                'finance_accounts.name as name',
                'transaction_expense.amount as amount',
                'transaction_expense.created_at as created_at',
                'transaction_expense.updated_at as updated_at',
                'transaction_expense.deleted_at as deleted_at',
            )
            ->selectRaw('\'expense\' as category_type')
            ->joinFinanceAccount()
            ->joinExpenseCategory()
            ->where('finance_accounts.user_id','=',$user->id);
    }

    public function scopeSearch($query, $search){
        return $query->where(function($q) use ($search){
            $q->whereRaw('LOWER(`finance_accounts`.`name`) LIKE ?', [
                    '%'.strtolower($search).'%'
                ])->orWhereRaw('LOWER(`expense_categories`.`name`) LIKE ?', [
                    '%'.strtolower($search).'%'
                ]);
        });
    }

    public function scopeFilterId($query){
        return $query->where('transaction_expense.id', '=', 0);
    }

    public function scopeFilterFinanceAccount($query, $filter){
        return $query->where('transaction_expense.finance_account_id', '=', $filter);
    }

    public function scopeFilterExpenseCategory($query, $filter){
        return $query->where('transaction_expense.expense_category_id', '=', $filter);
    }

    public function scopeJoinFinanceAccount($query)
    {
        return $query->join('finance_accounts', 'transaction_expense.finance_account_id', '=', 'finance_accounts.id');
    }

    public function scopeJoinExpenseCategory($query)
    {
        return $query->join('expense_categories', 'transaction_expense.expense_category_id', '=', 'expense_categories.id');
    }

    public function financeAccount()
    {
        $this->hasOne(FinanceAccount::class, 'id', 'finance_account_id');
    }

    public function expenseCategory()
    {
        $this->hasOne(ExpenseCategory::class, 'id', 'expense_category_id');
    }
}
