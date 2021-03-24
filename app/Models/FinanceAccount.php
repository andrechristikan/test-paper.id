<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\TrIncome;
use App\Models\TransactionExpense;
use App\Models\User;

class FinanceAccount extends Model
{
    
    protected $table = 'finance_accounts';

    protected $fillable = [
        'name',
    ];

    public function scopeGetByUserId($query)
    {
        $user = Auth::guard()->user();
        return $query->where('user_id','=',$user->id);
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereRaw('LOWER(`name`) LIKE ?', [
            '%'.strtolower($search).'%'
        ]);
    }


    public function scopeGetOneByUserIdAndId($query, $id)
    {
        return $query->getByUserId()->where('id', '=', $id);
    }


    public function trIncome()
    {
        return $this->hasMany(TrIncome::class, 'finance_account_id', 'id');
    }

    public function transactionExpense()
    {
        return $this->hasMany(TransactionExpense::class, 'finance_account_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


}
