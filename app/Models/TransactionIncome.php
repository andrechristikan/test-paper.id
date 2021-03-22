<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FinanceAccount;
use App\Models\IncomeCategory;
use Auth;
use DateTime;


class TransactionIncome extends Model
{
    use SoftDeletes;

    protected $table = 'transaction_income';

    protected $fillable = [
        'finance_account_id',
        'income_category_id',
        'amount'
    ];

    protected $attributes = [
        'amount' => 0,
    ];

    public function scopeGetByUserId($query)
    {
        return $query
            ->select(
                'transaction_income.finance_account_id as finance_account_id', 
                'transaction_income.income_category_id as category_id',
                'income_categories.name as category_name',
                'finance_accounts.user_id as user_id', 
                'finance_accounts.name as name',
                'transaction_income.amount as amount',
                'transaction_income.created_at as created_at',
                'transaction_income.updated_at as updated_at',
                'transaction_income.deleted_at as deleted_at',
            )
            ->selectRaw('\'income\' as category_type')
            ->joinFinanceAccount()
            ->joinIncomeCategory();
    }

    public function scopeGetByMonthAndYear($query, $year, $month){
        return $query
            ->whereYear('transaction_income.created_at', '=', $year)
            ->whereMonth('transaction_income.created_at', '=', $month);
    }

    public function scopeGetByDate($query, $year, $month, $day){
        $date = new DateTime($day.'-'.$month.'-'.$year);
        return $query->whereDate('transaction_income.created_at', '=', $date);
    }

    public function scopeSearch($query, $search){
        return $query->whereRaw('LOWER(`finance_accounts`.`name`) LIKE ?', [
                '%'.strtolower($search).'%'
            ])->orWhereRaw('LOWER(`income_categories`.`name`) LIKE ?', [
                '%'.strtolower($search).'%'
            ]);
    }

    public function scopeFilterId($query){
        return $query->where('transaction_income.id', '=', 0);
    }

    public function scopeFilterFinanceAccount($query, $filter){
        return $query->where('transaction_income.finance_account_id', '=', $filter);
    }

    public function scopeFilterIncomeCategory($query, $filter){
        return $query->where('transaction_income.income_category_id', '=', $filter);
    }

    public function scopeJoinFinanceAccount($query)
    {
        $user = Auth::guard()->user();
        return $query
            ->where('finance_accounts.user_id','=',$user->id)
            ->join('finance_accounts', 'transaction_income.finance_account_id', '=', 'finance_accounts.id');
    }

    public function scopeJoinIncomeCategory($query)
    {
        return $query->join('income_categories', 'transaction_income.income_category_id', '=', 'income_categories.id');
    }

    public function scopeSumIncome($query){
        return $query->joinFinanceAccount()->sum('transaction_income.amount');
    }
    

    public function financeAccount()
    {
        $this->hasOne(FinanceAccount::class, 'finance_account_id', 'id');
    }

    public function incomeCategory()
    {
        $this->hasOne(IncomeCategory::class, 'income_category_id', 'id');
    }

}
