<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TransactionIncome;
use App\Models\TransactionExpense;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = ( $request->query('page') && is_numeric($request->query('page')) ) ? (int) $request->query('page') : 1;
        $per_page = ( $request->query('per_page') && is_numeric($request->query('per_page')) ) ? (int) $request->query('per_page') : 10;
        $off_set= ($page - 1) * $per_page;

        $search = $request->query('search');
        $sort = $request->query('sort');
        $filters = $request->query('filters');

        if($sort && str_contains($sort, '@')){
            $sort = explode('@', $sort);

            if($sort[1] != 'asc' && $sort[1] != 'desc'){
                $sort[1] = 'asc';
            }
        }

        $transaction_income = TransactionIncome::getByUserId();
        $transaction_expense = TransactionExpense::getByUserId();
        
        if($search){
            $transaction_income->search($search);
            $transaction_expense->search($search);
        }

        if($filters){
            $transaction_filter = $this->filter($transaction_income, $transaction_expense, $filters);
            $transaction_income = $transaction_filter[0];
            $transaction_expense = $transaction_filter[1];
        }

        $transaction = $transaction_expense->unionAll($transaction_income);

        if(is_array($sort) && count($sort) > 1){
            ($sort[1] == 'desc') 
                ? 
                $transaction->orderByDesc($sort[0]) 
                :
                $transaction->orderBy($sort[0]);
        }

        $transaction = $transaction->offset($off_set)
            ->limit($per_page)->get();
            
        return response()
            ->json([
                'status_code' => 200,
                'message' => trans('transaction.get-all'),
                'data' => $transaction,
            ], 200);
    }

    private function filter($transaction_income, $transaction_expense, $filters){
        $filters = explode(';', $filters);
        foreach($filters as $key => $value){
            $filter = explode('@', $value);
            switch($filter[0]){
                case 'finance_account':
                    $transaction_income->filterFinanceAccount($filter[1]);
                    $transaction_expense->filterFinanceAccount($filter[1]);
                    break;
                case 'category_type':
                    $category = array_keys(array_filter($filters, function($var){
                        return str_contains($var, 'category@');
                    }));

                    if($category && count($category) > 0){
                        $category = explode('@', $filters[$category[0]]);
                        if($filter[1] == 'expense'){
                            $transaction_expense->filterExpenseCategory($category[1]);
                            $transaction_income->filterId();
                        }else if($filter[1] == 'income'){
                            $transaction_income->filterIncomeCategory($category[1]);
                            $transaction_expense->filterId();
                        }
                    }
                    break;
                default:
                    break;
            }
        }

        return [
            $transaction_income,
            $transaction_expense
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
