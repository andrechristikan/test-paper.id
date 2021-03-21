<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

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

    public function scopeSearchByName($query, $name)
    {
        return $query->whereRaw('LOWER(`name`) LIKE ?', [
            '%'.strtolower($name).'%'
        ]);
    }


    public function scopeGetOneByUserIdAndId($query, $id)
    {
        return $query->getByUserId()->where('id', '=', $id);
    }
}
